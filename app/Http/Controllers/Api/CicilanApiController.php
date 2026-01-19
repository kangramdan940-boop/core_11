<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransCicilanEmas;
use App\Models\TransCicilan;
use App\Models\TransCicilanPayment;
use App\Models\TransPaymentLog;
use App\Models\MasterCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class CicilanApiController extends Controller
{
    private const ADMIN_FEE = 25000;

    public function publicRecords(): \Illuminate\Http\JsonResponse
    {
        $items = TransCicilanEmas::with(['layanan','gramasi','latestAkad','agen'])
            ->orderByDesc('id')
            ->get();

        $data = [];
        foreach ($items as $r) {
            $data[] = $this->mapRecord($r);
        }

        return response()->json(['status' => true, 'data' => $data, 'meta' => ['count' => count($data)]]);
    }

    public function publicRecord(int $id): \Illuminate\Http\JsonResponse
    {
        $r = TransCicilanEmas::with(['layanan','gramasi','latestAkad','agen'])->find($id);
        return response()->json([
            'status' => (bool) $r,
            'data'   => $r ? $this->mapRecord($r) : null,
            'error'  => $r ? null : ['message' => 'Record tidak ditemukan']
        ]);
    }

    public function contracts(): \Illuminate\Http\JsonResponse
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->first();
        if (!$customer) {
            return response()->json(['status' => false, 'error' => ['message' => 'Customer tidak ditemukan']], 404);
        }

        $contracts = TransCicilan::where('master_customer_id', $customer->id)->orderByDesc('id')->get();
        $data = [];
        foreach ($contracts as $c) {
            $data[] = $this->mapContract($c, false);
        }

        return response()->json(['status' => true, 'data' => $data, 'meta' => ['count' => count($data)]]);
    }

    public function contract(TransCicilan $contract): \Illuminate\Http\JsonResponse
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->first();
        if (!$customer || (int)$contract->master_customer_id !== (int)$customer->id) {
            return response()->json(['status' => false, 'error' => ['message' => 'Kontrak tidak ditemukan']], 404);
        }

        $payments = $contract->cicilanPayments()->orderBy('cicilan_ke')->get();
        $pData = [];
        foreach ($payments as $p) {
            $pData[] = $this->mapPayment($p);
        }

        return response()->json([
            'status' => true,
            'data'   => $this->mapContract($contract, true),
            'meta'   => ['payments' => $pData]
        ]);
    }

    public function createContractFromRecord(Request $request, int $recordId): \Illuminate\Http\JsonResponse
    {
        $item = TransCicilanEmas::with(['layanan','latestAkad','gramasi'])->findOrFail($recordId);
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();

        $terpakai = (int) ($item->jumlah_keping_terpakai ?? 0);
        $sisaKeping = max(0, (int)$item->jumlah_keping_dibuka - $terpakai);
        if ($sisaKeping <= 0) {
            return response()->json(['status' => false, 'error' => ['message' => 'Tidak ada keping tersisa']], 422);
        }

        $minTenor = (int) (optional($item->layanan)->tenor_min_bulan ?? 3);
        $maxTenor = (int) (optional($item->layanan)->tenor_max_bulan ?? 24);

        $data = $request->validate([
            'tenor_bulan'            => ['required','integer','min:'.$minTenor,'max:'.$maxTenor],
            'dp_persen'              => ['required','numeric','in:10'],
            'jumlah_keping_diambil'  => ['required','integer','min:1','max:'.$sisaKeping],
            'file_bukti_bayar_dp'    => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
            'agree_terms'            => ['accepted'],
        ]);

        $hargaPerGram = (float) optional($item->latestAkad)->harga_per_gram_fix;
        if ($hargaPerGram <= 0) {
            return response()->json(['status' => false, 'error' => ['message' => 'Harga per gram belum diatur']], 422);
        }

        $gramPerKeping = (float) optional($item->gramasi)->gramasi;
        if ($gramPerKeping <= 0) {
            $gramPerKeping = (int)$item->jumlah_keping_dibuka > 0
                ? ((float)$item->total_gram_dibuka / (float)$item->jumlah_keping_dibuka)
                : 0.0;
        }
        if ($gramPerKeping <= 0) {
            return response()->json(['status' => false, 'error' => ['message' => 'Gramasi per keping belum tersedia']], 422);
        }

        $jumlahDiambil = (int)$data['jumlah_keping_diambil'];
        $gramasi = $jumlahDiambil * $gramPerKeping;
        $hargaTotalKontrak = $gramasi * $hargaPerGram;
        $dpAmount = $hargaTotalKontrak * ((float)$data['dp_persen']) / 100.0;
        $tenor = (int)$data['tenor_bulan'];
        $cicilanPerBulan = ($hargaTotalKontrak - $dpAmount) / $tenor;
        $sisaTagihan = $hargaTotalKontrak - $dpAmount;

        $dpBaseInt = (int) floor($dpAmount);
        $uniqueCode = $this->calcUniqueCode($jumlahDiambil, (float)$data['dp_persen']);
        $dpAmountWithCode = $dpBaseInt + $uniqueCode;

        $attempts = 0;
        while ($attempts < 5 && TransPaymentLog::where('ref_type','cicilan')->where('status','pending')->where('amount', $dpAmountWithCode + self::ADMIN_FEE)->exists()) {
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
            $sisaNow = max(0, (int)$record->jumlah_keping_dibuka - $terpakaiNow);
            if ($jumlahDiambil > $sisaNow) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jumlah_keping_diambil' => 'Jumlah keping melebihi sisa.'
                ]);
            }

            $kontrak = TransCicilan::create([
                'kode_kontrak'                    => 'KONTRAK-' . date('YmdHis') . '-' . mt_rand(100, 999),
                'master_customer_id'              => $customer->id,
                'master_agen_id'                  => $item->master_agen_id,
                'master_layanan_emas_cicilan_id'  => $item->master_layanan_emas_cicilan_id,
                'jumlah_keping_diambil'           => $jumlahDiambil,
                'gramasi'                         => $gramasi,
                'harga_per_gram_fix'              => $hargaPerGram,
                'harga_total_kontrak'             => $hargaTotalKontrak,
                'tenor_bulan'                     => $tenor,
                'dp_persen'                       => (float)$data['dp_persen'],
                'dp_amount'                       => $dpAmount,
                'cicilan_per_bulan'               => $cicilanPerBulan,
                'margin_persen'                   => 0,
                'margin_amount_total'             => 0,
                'jumlah_cicilan_terbayar'         => 0,
                'total_sudah_dibayar'             => 0,
                'sisa_tagihan'                    => $sisaTagihan,
                'status'                          => 'menunggu DP',
                'file_bukti_bayar_dp'             => $fileBuktiPath,
                'mulai_kontrak'                   => now()->toDateString(),
                'jatuh_tempo_kontrak'             => now()->addMonths($tenor)->toDateString(),
                'delivery_type'                   => 'pickup',
            ]);

            TransPaymentLog::create([
                'ref_type'        => 'cicilan',
                'ref_id'          => $kontrak->id,
                'kode_payment'    => 'DP-' . date('YmdHis') . '-' . mt_rand(100, 999),
                'amount'          => $dpAmountWithCode + self::ADMIN_FEE,
                'currency'        => 'IDR',
                'payment_method'  => 'manual_transfer',
                'payment_channel' => 'manual',
                'status'          => 'pending',
                'request_payload' => json_encode([
                    'dp_amount_base' => round($dpAmount, 2),
                    'unique_code'    => $uniqueCode,
                    'admin_fee'      => self::ADMIN_FEE,
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

        return response()->json([
            'status' => true,
            'data'   => $this->mapContract($kontrak, true),
            'meta'   => [
                'dp' => [
                    'base'       => (int) floor($dpAmount),
                    'uniqueCode' => $uniqueCode,
                    'adminFee'   => self::ADMIN_FEE,
                    'payable'    => (int) floor($dpAmount) + $uniqueCode + self::ADMIN_FEE,
                ]
            ]
        ], 201);
    }

    public function confirmPayment(Request $request, TransCicilanPayment $payment): \Illuminate\Http\JsonResponse
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) optional($payment->kontrak)->master_customer_id !== (int) $customer->id) {
            return response()->json(['status' => false, 'error' => ['message' => 'Pembayaran tidak ditemukan']], 404);
        }
        if ($payment->status !== 'pending') {
            return response()->json(['status' => false, 'error' => ['message' => 'Cicilan sudah diproses']], 422);
        }

        $data = $request->validate([
            'nominal_transfer' => ['required','numeric','min:0.01'],
            'nama_pengirim'    => ['required','string','max:150'],
            'bukti_transfer'   => ['required','file','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        $f = $request->file('bukti_transfer');
        $dir = public_path('payment_proofs');
        \Illuminate\Support\Facades\File::ensureDirectoryExists($dir);
        $name = uniqid('proof_') . '.' . $f->getClientOriginalExtension();
        $f->move($dir, $name);
        $path = 'payment_proofs/' . $name;
        $payment->bukti_transfer = $path;
        $payment->save();

        TransPaymentLog::create([
            'ref_type'        => 'cicilan_payment',
            'ref_id'          => $payment->id,
            'kode_payment'    => 'PAY-' . date('Ymd-His') . '-' . mt_rand(100, 999),
            'amount'          => (float)$data['nominal_transfer'],
            'currency'        => 'IDR',
            'payment_method'  => 'manual_transfer',
            'payment_channel' => 'manual',
            'status'          => 'pending',
            'request_payload' => json_encode(['sender_name' => $data['nama_pengirim'], 'proof_path' => $path], JSON_UNESCAPED_UNICODE),
        ]);

        return response()->json(['status' => true, 'data' => $this->mapPayment($payment)]);
    }

    private function mapRecord(TransCicilanEmas $r): array
    {
        $terpakai = (int) ($r->jumlah_keping_terpakai ?? 0);
        $sisa = max(0, (int)$r->jumlah_keping_dibuka - $terpakai);
        $pdf = optional($r->latestAkad)->file_pdf_url ?: null;
        if ($pdf && !\Illuminate\Support\Str::startsWith($pdf, ['http://','https://'])) {
            $pdf = asset($pdf);
        }

        return [
            'id'                     => (int)$r->id,
            'layananCode'            => (string) optional($r->layanan)->kode_layanan,
            'layananName'            => (string) optional($r->layanan)->nama_layanan,
            'tenorMin'               => (int) (optional($r->layanan)->tenor_min_bulan ?? 0),
            'tenorMax'               => (int) (optional($r->layanan)->tenor_max_bulan ?? 0),
            'dpMinPercent'           => (float) (optional($r->layanan)->dp_min_persen ?? 0),
            'dpMaxPercent'           => (float) (optional($r->layanan)->dp_max_persen ?? 0),
            'gramPerKeping'          => (float) (optional($r->gramasi)->gramasi ?? 0.0),
            'totalGramDibuka'        => (float) ($r->total_gram_dibuka ?? 0.0),
            'jumlahKepingDibuka'     => (int) ($r->jumlah_keping_dibuka ?? 0),
            'jumlahKepingTerpakai'   => (int) $terpakai,
            'kepingSisa'             => (int) $sisa,
            'hargaPerGramAkad'       => (float) (optional($r->latestAkad)->harga_per_gram_fix ?? 0.0),
            'pdfAkadUrl'             => $pdf,
            'agen'                   => $r->agen ? [
                'id'            => (int) $r->agen->id,
                'name'          => (string) ($r->agen->name ?? ''),
                'phone'         => (string) ($r->agen->phone_wa ?? ''),
                'email'         => (string) ($r->agen->email ?? ''),
                'address'       => (string) ($r->agen->address_line ?? ''),
                'kodeAgen'      => (string) ($r->agen->kode_agen ?? ''),
                'area'          => (string) ($r->agen->area ?? ''),
                'rekeningNomor' => (string) ($r->agen->rekening_nomor ?? ''),
            ] : null,
        ];
    }

    private function mapContract(TransCicilan $c, bool $withRelations): array
    {
        $base = [
            'id'                 => (int)$c->id,
            'kodeKontrak'        => (string)$c->kode_kontrak,
            'gramasi'            => (float)$c->gramasi,
            'hargaPerGramFix'    => (float)$c->harga_per_gram_fix,
            'hargaTotalKontrak'  => (float)$c->harga_total_kontrak,
            'tenorBulan'         => (int)$c->tenor_bulan,
            'dpPersen'           => (float)$c->dp_persen,
            'dpAmount'           => (float)$c->dp_amount,
            'cicilanPerBulan'    => (float)$c->cicilan_per_bulan,
            'sisaTagihan'        => (float)$c->sisa_tagihan,
            'status'             => (string)$c->status,
            'mulaiKontrak'       => (string)$c->mulai_kontrak,
            'jatuhTempoKontrak'  => (string)$c->jatuh_tempo_kontrak,
        ];

        if (!$withRelations) return $base;

        return $base + [
            'customerId' => (int)$c->master_customer_id,
            'agenId'     => (int)$c->master_agen_id,
            'layananId'  => (int)$c->master_layanan_emas_cicilan_id,
        ];
    }

    private function mapPayment(TransCicilanPayment $p): array
    {
        return [
            'id'          => (int)$p->id,
            'kontrakId'   => (int)$p->trans_cicilan_id,
            'cicilanKe'   => (int)$p->cicilan_ke,
            'dueDate'     => (string)$p->due_date,
            'amountDue'   => (float)$p->amount_due,
            'status'      => (string)$p->status,
        ];
    }

    private function calcUniqueCode(int $jumlah, float $dpPercent): int
    {
        $base = ($jumlah * 37) + (int) round($dpPercent * 10);
        return ($base % 900) + 100;
    }
}