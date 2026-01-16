<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\MasterCustomer;
use App\Models\MasterGoldReadyStock;
use App\Models\MasterLayananEmasCicilan;
use App\Models\TransCicilanAkad;
use App\Models\TransCicilan;
use App\Models\TransCicilanPayment;
use App\Models\TransPaymentLog;
use App\Models\TransCicilanEmas;

class CustomerCicilanController extends Controller
{
    public function index()
    {
        $cicilanEmas = TransCicilanEmas::with(['layanan', 'gramasi', 'latestAkad'])
            ->orderByDesc('id')
            ->get();

        $customer = MasterCustomer::where('sys_user_id', Auth::id())->first();
        $contracts = $customer
            ? TransCicilan::where('master_customer_id', $customer->id)->orderByDesc('id')->paginate(10)
            : collect();

        return view('front.customer.cicilan.index', compact('cicilanEmas', 'contracts', 'customer'));
    }

    /**
     * Merender halaman detail master layanan cicilan.
     */
    public function layanan(MasterLayananEmasCicilan $layanan)
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->first();
        $akad = TransCicilanAkad::whereHas('kontrak', function ($q) use ($layanan) {
            $q->where('master_layanan_emas_cicilan_id', $layanan->id);
        })->orderByDesc('id')->first();
        return view('front.customer.cicilan.layanan', compact('layanan', 'customer', 'akad'));
    }

    public function choose(string $record)
    {
        $id = (int) decrypt($record);
        $item = TransCicilanEmas::with(['layanan','gramasi','latestAkad','agen'])->findOrFail($id);
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->first();
        return view('front.customer.cicilan.choose', compact('item','customer'));
    }

    public function storeFromRecord(Request $request, string $record)
    {
        $id = (int) decrypt($record);
        $item = TransCicilanEmas::with(['layanan','latestAkad','agen'])->findOrFail($id);
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();

        $terpakai = (int) ($item->jumlah_keping_terpakai ?? 0);
        $sisaKeping = max(0, (int) $item->jumlah_keping_dibuka - $terpakai);
        if ($sisaKeping <= 0) {
            return back()->withErrors(['jumlah_keping_diambil' => 'Tidak ada keping tersisa untuk diambil.'])->withInput();
        }

        $minTenor = (int) (optional($item->layanan)->tenor_min_bulan ?? 3);
        $maxTenor = (int) (optional($item->layanan)->tenor_max_bulan ?? 24);
        $minDp    = (float) (optional($item->layanan)->dp_min_persen ?? 0);
        $maxDp    = (float) (optional($item->layanan)->dp_max_persen ?? 50);
        $dpRules = ['required','numeric','in:5,10,20'];

        $data = $request->validate([
            'tenor_bulan'   => ['required','integer','min:'.$minTenor,'max:'.$maxTenor],
            'dp_persen'     => $dpRules,
            'jumlah_keping_diambil' => ['required','integer','min:1','max:'.$sisaKeping],
            'file_bukti_bayar_dp' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
            'agree_terms'   => ['accepted'],
        ]);

        $hargaPerGram = (float) optional($item->latestAkad)->harga_per_gram_fix;
        if ($hargaPerGram <= 0) {
            return back()->withErrors(['tenor_bulan' => 'Harga per gram pada Akad belum diatur.'])->withInput();
        }

        $gramPerKeping = (float) optional($item->gramasi)->gramasi;
        if ($gramPerKeping <= 0) {
            return back()->withErrors(['jumlah_keping_diambil' => 'Gramasi per keping belum diatur.'])->withInput();
        }
        $jumlahDiambil = (int) $data['jumlah_keping_diambil'];
        $gramasi = $jumlahDiambil * $gramPerKeping;
        $hargaTotalKontrak = $gramasi * $hargaPerGram;
        $dpAmount = $hargaTotalKontrak * ((float) $data['dp_persen']) / 100.0;
        $tenor = (int) $data['tenor_bulan'];
        $cicilanPerBulan = ($hargaTotalKontrak - $dpAmount) / $tenor;
        $sisaTagihan = $hargaTotalKontrak - $dpAmount;

        $dpBaseInt = (int) floor($dpAmount);
        $uniqueCode = (((int) $jumlahDiambil) * 37 + (int) round(((float) $data['dp_persen']) * 10)) % 900 + 100;
        $dpAmountWithCode = $dpBaseInt + $uniqueCode;
        // cek potensi bentrok amount di payment log pending
        $attempts = 0;
        while ($attempts < 5 && \App\Models\TransPaymentLog::where('ref_type','cicilan')->where('status','pending')->where('amount',$dpAmountWithCode)->exists()) {
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

        DB::transaction(function () use ($item, $customer, $gramasi, $hargaPerGram, $hargaTotalKontrak, $tenor, $data, $dpAmount, $cicilanPerBulan, $sisaTagihan, $fileBuktiPath, $uniqueCode, $dpAmountWithCode, &$kontrak, $jumlahDiambil) {
            $record = TransCicilanEmas::where('id', $item->id)->lockForUpdate()->first();
            $terpakaiNow = (int) ($record->jumlah_keping_terpakai ?? 0);
            $sisaNow = max(0, (int) $record->jumlah_keping_dibuka - $terpakaiNow);
            if ($jumlahDiambil > $sisaNow) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jumlah_keping_diambil' => 'Jumlah keping yang diambil melebihi sisa keping.'
                ]);
            }

            $kontrak = TransCicilan::create([
                'kode_kontrak'              => 'KONTRAK-' . date('YmdHis') . '-' . mt_rand(100, 999),
                'master_customer_id'        => $customer->id,
                'master_agen_id'            => $item->master_agen_id,
                'master_layanan_emas_cicilan_id' => $item->master_layanan_emas_cicilan_id,
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

            \App\Models\TransPaymentLog::create([
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

            $record->increment('jumlah_keping_terpakai', $jumlahDiambil);
        });

        return redirect()->route('customer.cicilan.show', $kontrak)->with('success', 'Kontrak cicilan dibuat.');
    }

    public function stock(string $stock)
    {
        $id = (int) decrypt($stock);
        $stock = MasterGoldReadyStock::findOrFail($id);
        if (!$stock->is_active || $stock->status !== 'available') {
            abort(404);
        }
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->first();
        return view('front.customer.cicilan.stock', compact('stock', 'customer'));
    }

    public function store(Request $request)
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'stock_id'          => ['required', 'integer', 'exists:master_gold_ready_stock,id'],
            'tenor_bulan'       => ['required', 'integer', 'min:3', 'max:24'],
            'dp_persen'         => ['required','numeric','in:3,6,12,24'],
            'delivery_type'     => ['required', 'in:ship,pickup'],
            'shipping_name'     => ['nullable', 'string', 'max:150'],
            'shipping_phone'    => ['nullable', 'string', 'max:30'],
            'shipping_address'  => ['nullable', 'string', 'max:255'],
            'shipping_city'     => ['nullable', 'string', 'max:100'],
            'shipping_province' => ['nullable', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:10'],
        ]);

        $stock = MasterGoldReadyStock::findOrFail((int) $data['stock_id']);
        if (!$stock->is_active || $stock->status !== 'available') {
            abort(404);
        }

        $gramasi = (float) $stock->gramasi;
        $hargaPerGram = (float) $stock->harga_jual_fix;
        $hargaTotalKontrak = $gramasi * $hargaPerGram;
        $dpAmount = $hargaTotalKontrak * ((float) $data['dp_persen']) / 100.0;
        $tenor = (int) $data['tenor_bulan'];
        $cicilanPerBulan = ($hargaTotalKontrak - $dpAmount) / $tenor;
        $sisaTagihan = $hargaTotalKontrak - $dpAmount;

        $kontrak = TransCicilan::create([
            'kode_kontrak'              => 'KONTRAK-' . date('Ymd-His') . '-' . mt_rand(100, 999),
            'master_customer_id'        => $customer->id,
            'master_agen_id'            => $stock->master_agen_id,
            'master_gold_ready_stock_id'=> $stock->id,
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
            'mulai_kontrak'             => now()->toDateString(),
            'jatuh_tempo_kontrak'       => now()->addMonths($tenor)->toDateString(),
            'delivery_type'             => $data['delivery_type'],
            'shipping_name'             => $data['shipping_name'] ?? null,
            'shipping_phone'            => $data['shipping_phone'] ?? null,
            'shipping_address'          => $data['shipping_address'] ?? null,
            'shipping_city'             => $data['shipping_city'] ?? null,
            'shipping_province'         => $data['shipping_province'] ?? null,
            'shipping_postal_code'      => $data['shipping_postal_code'] ?? null,
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

        return redirect()->route('customer.cicilan.show', $kontrak)->with('success', 'Kontrak cicilan dibuat.');
    }

    public function show(TransCicilan $contract)
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $contract->master_customer_id !== (int) $customer->id) {
            abort(404);
        }
        $payments = $contract->cicilanPayments()->orderBy('cicilan_ke')->get();
        return view('front.customer.cicilan.show', compact('contract', 'payments'));
    }

    public function confirmPayment(Request $request, TransCicilanPayment $payment)
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) optional($payment->kontrak)->master_customer_id !== (int) $customer->id) {
            abort(404);
        }
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Cicilan ini sudah diproses.');
        }

        $data = $request->validate([
            'nominal_transfer' => ['required', 'numeric', 'min:0.01'],
            'nama_pengirim'    => ['required', 'string', 'max:150'],
            'bukti_transfer'   => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $f = $request->file('bukti_transfer');
        $dir = public_path('payment_proofs');
        if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
        $name = uniqid('proof_') . '.' . $f->getClientOriginalExtension();
        $f->move($dir, $name);
        $path = 'payment_proofs/' . $name;

        TransPaymentLog::create([
            'ref_type'        => 'cicilan_payment',
            'ref_id'          => $payment->id,
            'kode_payment'    => 'PAY-' . date('Ymd-His') . '-' . mt_rand(100, 999),
            'amount'          => (float) $data['nominal_transfer'],
            'currency'        => 'IDR',
            'payment_method'  => 'manual_transfer',
            'provider'        => null,
            'payment_channel' => 'manual',
            'status'          => 'pending',
            'request_payload' => json_encode([
                'sender_name' => $data['nama_pengirim'],
                'proof_path'  => $path,
            ], JSON_UNESCAPED_UNICODE),
        ]);

        return redirect()
            ->route('customer.cicilan.show', $payment->trans_cicilan_id)
            ->with('success', 'Konfirmasi pembayaran cicilan terkirim. Menunggu verifikasi admin.');
    }
}