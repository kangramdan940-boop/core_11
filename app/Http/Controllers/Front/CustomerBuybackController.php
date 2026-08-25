<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\MasterCustomer;
use App\Models\TransBuyback;
use App\Models\TransBuybackLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerBuybackController extends Controller
{
    /**
     * Daftar pengajuan buyback milik customer + form pengajuan baru.
     */
    public function index()
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();

        $items = TransBuyback::where('master_customer_id', $customer->id)
            ->orderByDesc('id')
            ->get();

        return view('front.customer.buyback.index', compact('items', 'customer'));
    }

    /**
     * Form pengajuan buyback baru. Bisa di-prefill dari etalase buyback (opsional).
     */
    public function create(Request $request)
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->first();

        $prefill = null;
        if ($request->filled('ref')) {
            try {
                $id = (int) decrypt((string) $request->query('ref'));
                $row = DB::table('wp_etalase_emas')
                    ->where('code', 'buyback')
                    ->where('id', $id)
                    ->first(['id', 'brand', 'berat', 'buyback']);
                if ($row) {
                    $prefill = $row;
                }
            } catch (\Throwable $e) {
                $prefill = null;
            }
        }

        // Daftar harga buyback aktif sebagai referensi
        $etalaseBuyback = DB::table('wp_etalase_emas')
            ->where('code', 'buyback')
            ->orderBy('brand')
            ->get(['id', 'brand', 'berat', 'buyback', 'status']);

        // Daftar brand emas dari data buyback admin (wp_etalase_emas code=buyback)
        $brandOptions = DB::table('wp_etalase_emas')
            ->where('code', 'buyback')
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->orderBy('brand')
            ->get(['id', 'brand', 'berat', 'buyback', 'status']);

        // Opsi "Available Buyback" untuk dipilih customer: brand - berat - harga buyback
        $buybackOptions = $etalaseBuyback->map(function ($row) {
            $beratNum = (float) preg_replace('/[^0-9.,]/', '', str_replace(',', '.', (string) $row->berat));
            $hargaBuyback = (int) $row->buyback;
            $beratLabel = trim((string) $row->berat) !== '' ? trim((string) $row->berat) : ($beratNum . ' gr');
            return [
                'ref'         => encrypt((string) $row->id),
                'brand'       => trim((string) $row->brand),
                'berat_gram'  => $beratNum,
                'berat_label' => $beratLabel,
                'buyback'     => $hargaBuyback,
                'label'       => trim((string) $row->brand) . ' - ' . $beratLabel . ' - Rp ' . number_format($hargaBuyback, 0, ',', '.'),
            ];
        })->values();

        return view('front.customer.buyback.create', compact('customer', 'prefill', 'etalaseBuyback', 'buybackOptions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ref'                => ['required', 'string'],
            'qty'                => ['nullable', 'integer', 'min:1'],
            'kondisi'            => ['nullable', 'string', 'max:100'],
            'ada_sertifikat'     => ['nullable', 'boolean'],
            'metode_serah'       => ['required', Rule::in(['kirim', 'datang_ke_lokasi'])],
            'bank_nama'          => ['required', 'string', 'max:100'],
            'rekening_nomor'     => ['required', 'string', 'max:100'],
            'rekening_atas_nama' => ['required', 'string', 'max:150'],
            'catatan'            => ['nullable', 'string'],
        ], [
            'ref.required' => 'Silakan pilih item buyback dari daftar yang tersedia.',
        ]);

        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();

        // Sumber kebenaran: item etalase buyback yang dipilih (brand, berat, harga)
        try {
            $etalaseId = (int) decrypt((string) $data['ref']);
        } catch (\Throwable $e) {
            return back()->withErrors(['ref' => 'Item buyback tidak valid. Silakan pilih ulang.'])->withInput();
        }

        $row = DB::table('wp_etalase_emas')
            ->where('code', 'buyback')
            ->where('id', $etalaseId)
            ->first(['id', 'brand', 'berat', 'buyback']);

        if (!$row) {
            return back()->withErrors(['ref' => 'Item buyback tidak ditemukan. Silakan pilih ulang.'])->withInput();
        }

        $brand         = trim((string) $row->brand);
        $beratGram     = (float) preg_replace('/[^0-9.,]/', '', str_replace(',', '.', (string) $row->berat));
        $hargaEstimasi = (float) $row->buyback;

        $attrs = TransBuyback::buildAttributesForDraft(
            customerId: (int) $customer->id,
            etalaseBuybackId: $etalaseId,
            brand: $brand,
            beratGram: $beratGram,
            qty: isset($data['qty']) ? (int) $data['qty'] : 1,
            hargaBuybackEstimasi: $hargaEstimasi,
            metodeSerah: $data['metode_serah'],
            kondisi: $data['kondisi'] ?? null,
            adaSertifikat: (bool) ($data['ada_sertifikat'] ?? false),
            rekening: [
                'bank_nama'          => $data['bank_nama'],
                'rekening_nomor'     => $data['rekening_nomor'],
                'rekening_atas_nama' => $data['rekening_atas_nama'],
            ],
            catatan: $data['catatan'] ?? null
        );

        $buyback = DB::transaction(function () use ($attrs) {
            $buyback = TransBuyback::create($attrs);
            TransBuybackLog::create([
                'trans_buyback_id' => $buyback->id,
                'status'           => 'pending_review',
                'description'      => 'Pengajuan buyback dibuat oleh customer.',
            ]);
            return $buyback;
        });

        return redirect()
            ->route('customer.buyback.show', ['buyback' => encrypt((string) $buyback->id)])
            ->with('success', 'Pengajuan buyback terkirim. Menunggu verifikasi admin.');
    }

    public function show(string $buyback)
    {
        $id = (int) decrypt($buyback);
        $trx = TransBuyback::findOrFail($id);

        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $trx->master_customer_id !== (int) $customer->id) {
            abort(404);
        }

        $logs = TransBuybackLog::where('trans_buyback_id', $trx->id)
            ->orderByDesc('id')
            ->get();

        return view('front.customer.buyback.show', compact('trx', 'logs'));
    }

    /**
     * Customer menyetujui harga final yang ditetapkan admin.
     */
    public function approve(string $buyback)
    {
        $id = (int) decrypt($buyback);
        $trx = TransBuyback::findOrFail($id);

        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $trx->master_customer_id !== (int) $customer->id) {
            abort(404);
        }

        if ($trx->status !== 'priced') {
            return redirect()
                ->route('customer.buyback.show', ['buyback' => encrypt((string) $trx->id)])
                ->with('error', 'Transaksi belum siap untuk disetujui.');
        }

        DB::transaction(function () use ($trx) {
            $trx->status = 'approved';
            $trx->approved_at = now();
            $trx->save();

            TransBuybackLog::create([
                'trans_buyback_id' => $trx->id,
                'status'           => 'approved',
                'description'      => 'Customer menyetujui harga buyback final.',
            ]);
        });

        return redirect()
            ->route('customer.buyback.show', ['buyback' => encrypt((string) $trx->id)])
            ->with('success', 'Harga disetujui. Dana akan segera ditransfer oleh admin.');
    }

    /**
     * Customer membatalkan pengajuan (selama belum dibayar).
     */
    public function cancel(string $buyback)
    {
        $id = (int) decrypt($buyback);
        $trx = TransBuyback::findOrFail($id);

        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $trx->master_customer_id !== (int) $customer->id) {
            abort(404);
        }

        if (!in_array($trx->status, ['pending_review', 'priced'], true)) {
            return redirect()
                ->route('customer.buyback.show', ['buyback' => encrypt((string) $trx->id)])
                ->with('error', 'Transaksi tidak dapat dibatalkan pada status ini.');
        }

        DB::transaction(function () use ($trx) {
            $trx->status = 'cancelled';
            $trx->cancelled_at = now();
            $trx->save();

            TransBuybackLog::create([
                'trans_buyback_id' => $trx->id,
                'status'           => 'cancelled',
                'description'      => 'Pengajuan dibatalkan oleh customer.',
            ]);
        });

        return redirect()
            ->route('customer.buyback.show', ['buyback' => encrypt((string) $trx->id)])
            ->with('success', 'Pengajuan buyback dibatalkan.');
    }
}
