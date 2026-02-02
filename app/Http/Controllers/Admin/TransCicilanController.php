<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransCicilan;
use App\Models\TransCicilanEmas;
use App\Models\MasterCustomer;
use App\Models\TransCicilanPayment;
use App\Models\TransPaymentLog;
use Illuminate\Support\Facades\DB;

class TransCicilanController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $statusParam = (string) ($request->query('status') ?? '');
        $map = [
            'active' => 'active',
            'canceled' => 'canceled',
            'cancelled' => 'canceled',
            'menunggu dp' => 'menunggu DP',
            'menunggu-dp' => 'menunggu DP',
        ];
        $key = strtolower($statusParam);
        $statusFilter = $map[$key] ?? null;

        $customerParam = trim((string) ($request->query('customer') ?? ''));

        $query = TransCicilan::with(['customer', 'agen'])->orderByDesc('id');
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        if ($customerParam !== '') {
            $query->whereHas('customer', function ($q) use ($customerParam) {
                $q->where('full_name', 'like', '%'.$customerParam.'%');
            });
        }
        $contracts = $query->get();
        $customers = MasterCustomer::where('is_active', true)->orderBy('full_name')->get();
        $records = TransCicilanEmas::with(['layanan','agen','gramasi','latestAkad'])->orderByDesc('id')->get();

        return view('admin.trans_cicilan.index', compact('contracts', 'customers', 'records'));
    }

    public function show(TransCicilan $contract)
    {
        $payments = $contract->cicilanPayments()
            ->orderBy('cicilan_ke')
            ->get();

        return view('admin.trans_cicilan.show', compact('contract', 'payments'));
    }

    public function paymentsData(TransCicilan $contract): \Illuminate\Http\JsonResponse
    {
        $items = $contract->cicilanPayments()
            ->orderBy('cicilan_ke')
            ->get(['id','cicilan_ke','due_date','amount_due','status']);

        return response()->json(['status' => true, 'data' => $items]);
    }

    public function updateStatus(\Illuminate\Http\Request $request, TransCicilan $contract)
    {
        $allowed = ['menunggu DP','active','pembayaran telat','sudah di bayar','selesai','canceled'];
        $data = $request->validate([
            'status' => ['required', \Illuminate\Validation\Rule::in($allowed)],
        ]);

        $new = (string) $data['status'];
        if ($contract->status === $new) {
            return redirect()->route('admin.trans.cicilan.show', $contract)->with('success', 'Status tidak berubah.');
        }

        $old = (string) $contract->status;
        $contract->status = $new;

        if ($old === 'menunggu DP' && $new === 'active') {
            if ($contract->master_gold_ready_stock_id) {
                $stock = \App\Models\MasterGoldReadyStock::find($contract->master_gold_ready_stock_id);
                if ($stock && $stock->status === 'available') {
                    $stock->status = 'reserved';
                    $stock->save();
                }
            }
        }

        if ($new === 'selesai' && !$contract->completed_at) {
            $contract->completed_at = now();
        } elseif ($new === 'canceled' && !$contract->cancelled_at) {
            $contract->cancelled_at = now();
        }

        $contract->save();

        return redirect()->route('admin.trans.cicilan.show', $contract)->with('success', 'Status kontrak diperbarui.');
    }

    public function uploadDpProof(\Illuminate\Http\Request $request, TransCicilan $contract)
    {
        $data = $request->validate([
            'bukti_dp' => ['required','file','mimes:jpg,jpeg,png,webp,pdf','max:5120'],
        ]);
        $f = $request->file('bukti_dp');
        $dir = public_path('bukti_dp');
        if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
        $name = uniqid('dp_') . '.' . $f->getClientOriginalExtension();
        $f->move($dir, $name);
        $path = 'bukti_dp/' . $name;

        $contract->file_bukti_bayar_dp = $path;
        $contract->save();

        return redirect()->route('admin.trans.cicilan.index', ['status' => 'active'])->with('success', 'Bukti DP berhasil diupload/diupdate.');
    }

    public function cancelWaitingDpAll(\Illuminate\Http\Request $request)
    {
        $count = 0;
        TransCicilan::where('status', 'menunggu DP')->chunkById(100, function ($items) use (&$count) {
            foreach ($items as $contract) {
                $contract->status = 'canceled';
                if (!$contract->cancelled_at) {
                    $contract->cancelled_at = now();
                }
                $contract->save();
                $count++;
            }
        });

        return redirect()->route('admin.trans.cicilan.index')->with('success', 'Berhasil membatalkan ' . $count . ' kontrak menunggu DP.');
    }

    public function storeFromRecord(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'trans_cicilan_emas_id'   => ['required','integer','exists:trans_cicilan_emas,id'],
            'master_customer_id'      => ['required','integer','exists:master_customer,id'],
            'tenor_bulan'             => ['required','integer','min:3','max:24'],
            'dp_persen'               => ['required','numeric','in:5,10,20'],
            'jumlah_keping_diambil'   => ['required','integer','min:1'],
            'file_bukti_bayar_dp'     => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        ]);

        $record = TransCicilanEmas::with(['layanan','latestAkad','agen','gramasi'])->findOrFail((int) $data['trans_cicilan_emas_id']);
        $customer = MasterCustomer::findOrFail((int) $data['master_customer_id']);

        $terpakai = (int) ($record->jumlah_keping_terpakai ?? 0);
        $sisaKeping = max(0, (int) $record->jumlah_keping_dibuka - $terpakai);
        if ($sisaKeping <= 0) {
            return back()->withErrors(['jumlah_keping_diambil' => 'Tidak ada keping tersisa untuk diambil.'])->withInput();
        }

        $jumlahDiambil = (int) $data['jumlah_keping_diambil'];
        if ($jumlahDiambil > $sisaKeping) {
            return back()->withErrors(['jumlah_keping_diambil' => 'Jumlah keping yang diambil melebihi sisa keping.'])->withInput();
        }

        $hargaPerGram = (float) optional($record->latestAkad)->harga_per_gram_fix;
        if ($hargaPerGram <= 0) {
            return back()->withErrors(['tenor_bulan' => 'Harga per gram pada Akad belum diatur.'])->withInput();
        }

        $gramPerKeping = (float) optional($record->gramasi)->gramasi;
        if ($gramPerKeping <= 0) {
            return back()->withErrors(['jumlah_keping_diambil' => 'Gramasi per keping belum diatur.'])->withInput();
        }

        $gramasi = $jumlahDiambil * $gramPerKeping;
        $hargaTotalKontrak = $gramasi * $hargaPerGram;
        $dpAmount = $hargaTotalKontrak * ((float) $data['dp_persen']) / 100.0;
        $tenor = (int) $data['tenor_bulan'];
        $cicilanPerBulan = ($hargaTotalKontrak - $dpAmount) / $tenor;
        $sisaTagihan = $hargaTotalKontrak - $dpAmount;

        $dpBaseInt = (int) floor($dpAmount);
        $uniqueCode = (((int) $jumlahDiambil) * 37 + (int) round(((float) $data['dp_persen']) * 10)) % 900 + 100;
        $dpAmountWithCode = $dpBaseInt + $uniqueCode;
        $attempts = 0;
        while ($attempts < 5 && TransPaymentLog::where('ref_type','cicilan')->where('status','pending')->where('amount',$dpAmountWithCode)->exists()) {
            $uniqueCode = ($uniqueCode % 999) + 1;
            $dpAmountWithCode = $dpBaseInt + $uniqueCode;
            $attempts++;
        }

        $fileBuktiPath = null;
        if ($request->hasFile('file_bukti_bayar_dp')) {
            $f = $request->file('file_bukti_bayar_dp');
            $dir = public_path('bukti_dp');
            if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
            $name = uniqid('dp_') . '.' . $f->getClientOriginalExtension();
            $f->move($dir, $name);
            $fileBuktiPath = 'bukti_dp/' . $name;
        }

        $kontrak = null;
        DB::transaction(function () use ($record, $customer, $gramasi, $hargaPerGram, $hargaTotalKontrak, $tenor, $data, $dpAmount, $cicilanPerBulan, $sisaTagihan, $fileBuktiPath, $uniqueCode, $dpAmountWithCode, &$kontrak, $jumlahDiambil) {
            $locked = TransCicilanEmas::where('id', $record->id)->lockForUpdate()->first();
            $terpakaiNow = (int) ($locked->jumlah_keping_terpakai ?? 0);
            $sisaNow = max(0, (int) $locked->jumlah_keping_dibuka - $terpakaiNow);
            if ($jumlahDiambil > $sisaNow) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jumlah_keping_diambil' => 'Jumlah keping yang diambil melebihi sisa keping.'
                ]);
            }

            $kontrak = TransCicilan::create([
                'kode_kontrak'              => 'KONTRAK-' . date('YmdHis') . '-' . mt_rand(100, 999),
                'master_customer_id'        => $customer->id,
                'master_agen_id'            => $record->master_agen_id,
                'master_layanan_emas_cicilan_id' => $record->master_layanan_emas_cicilan_id,
                'jumlah_keping_diambil'     => $jumlahDiambil,
                'gramasi'                   => $gramasi,
                'harga_per_gram_fix'        => $hargaPerGram,
                'harga_total_kontrak'       => $hargaTotalKontrak,
                'tenor_bulan'               => $tenor,
                'dp_persen'                 => (float) $data['dp_persen'],
                'dp_amount'                 => $dpAmount,
                'cicilan_per_bulan'         => $cicilanPerBulan,
                'margin_persen'             => 0,
                'margin_amount_total'       => 0,
                'jumlah_cicilan_terbayar'   => 0,
                'total_sudah_dibayar'       => 0,
                'sisa_tagihan'              => $sisaTagihan,
                'status'                    => 'menunggu DP',
                'file_bukti_bayar_dp'       => $fileBuktiPath,
                'mulai_kontrak'             => now()->toDateString(),
                'jatuh_tempo_kontrak'       => now()->addMonths($tenor)->toDateString(),
                'delivery_type'             => 'pickup',
            ]);

            TransPaymentLog::create([
                'ref_type'       => 'cicilan',
                'ref_id'         => $kontrak->id,
                'kode_payment'   => 'DP-' . date('YmdHis') . '-' . mt_rand(100, 999),
                'amount'         => $dpAmountWithCode,
                'currency'       => 'IDR',
                'payment_method' => 'manual_transfer',
                'payment_channel'=> 'manual',
                'status'         => 'pending',
                'request_payload'=> json_encode([
                    'dp_amount_base' => round($dpAmount, 2),
                    'unique_code'    => $uniqueCode,
                ], JSON_UNESCAPED_UNICODE),
            ]);

            for ($i = 1; $i <= $tenor; $i++) {
                TransCicilanPayment::create([
                    'trans_cicilan_id' => $kontrak->id,
                    'cicilan_ke'       => $i,
                    'due_date'         => now()->addMonths($i)->toDateString(),
                    'amount_due'       => $cicilanPerBulan,
                    'status'           => 'pending',
                ]);
            }

            $locked->increment('jumlah_keping_terpakai', $jumlahDiambil);
        });

        return redirect()->route('admin.trans.cicilan.show', $kontrak)->with('success', 'Kontrak cicilan dibuat.');
    }
}