<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterCustomer;
use App\Models\MasterGoldReadyStock;
use App\Models\TransCicilan;
use App\Models\TransCicilanPayment;
use App\Models\TransPaymentLog;

class CustomerCicilanController extends Controller
{
    public function index()
    {
        $stocks = MasterGoldReadyStock::where('is_active', true)
            ->where('status', 'available')
            ->orderBy('brand')
            ->orderBy('gramasi')
            ->get();

        $customer = MasterCustomer::where('sys_user_id', Auth::id())->first();
        $contracts = $customer
            ? TransCicilan::where('master_customer_id', $customer->id)->orderByDesc('id')->paginate(10)
            : collect();

        return view('front.customer.cicilan.index', compact('stocks', 'contracts', 'customer'));
    }

    public function stock(MasterGoldReadyStock $stock)
    {
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
            'dp_persen'         => ['required', 'numeric', 'min:0', 'max:50'],
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
            'status'                    => 'active',
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
            'bukti_transfer'   => ['required', 'image', 'max:3072'],
        ]);

        $path = $request->file('bukti_transfer')->store('payment_proofs', 'public');

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