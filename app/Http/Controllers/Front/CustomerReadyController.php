<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\MasterCustomer;
use App\Models\MasterGoldReadyStock;
use App\Models\MasterProdukDanLayanan;
use App\Models\MasterGramasiEmas;
use App\Models\TransReady;
use App\Models\TransPaymentLog;
use App\Models\TransReadyLog;

class CustomerReadyController extends Controller
{
    public function index()
    {
        $stocks = MasterGoldReadyStock::where('is_active', true)
            ->where('status', 'available')
            ->orderBy('brand')
            ->orderBy('gramasi')
            ->paginate(20);

        return view('front.customer.ready.index', compact('stocks'));
    }

    public function buy(string $stock)
    {
        $id = (int) decrypt($stock);
        $stock = MasterGoldReadyStock::findOrFail($id);
        if (!$stock->is_active || $stock->status !== 'available') {
            abort(404);
        }
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->first();
        return view('front.customer.ready.buy', compact('stock', 'customer'));
    }

    public function stock(string $stock)
    {
        $id = (int) decrypt($stock);
        $stock = MasterGoldReadyStock::findOrFail($id);
        if (!$stock->is_active || $stock->status !== 'available') {
            abort(404);
        }
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->first();
        return view('front.customer.ready.stock', compact('stock', 'customer'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ready_stock_id'               => ['required', 'string'],
            'id_master_produk_dan_layanan' => ['nullable', 'integer', 'exists:master_produk_dan_layanan,id'],
            'qty'                          => ['nullable', 'integer', 'min:1'],
            'delivery_type'                => ['required', Rule::in(['ship','pickup','titip_agen'])],
            'shipping_name'                => ['nullable', 'string', 'max:150'],
            'shipping_phone'               => ['nullable', 'string', 'max:50'],
            'shipping_address'             => ['nullable', 'string', 'max:255'],
            'shipping_city'                => ['nullable', 'string', 'max:100'],
            'shipping_province'            => ['nullable', 'string', 'max:100'],
            'shipping_postal_code'         => ['nullable', 'string', 'max:10'],
            'catatan'                      => ['nullable', 'string'],
        ]);

        $data['qty'] = isset($data['qty']) ? (int)$data['qty'] : 1;

        if ($data['delivery_type'] !== 'ship') {
            $data['shipping_name'] = null;
            $data['shipping_phone'] = null;
            $data['shipping_address'] = null;
            $data['shipping_city'] = null;
            $data['shipping_province'] = null;
            $data['shipping_postal_code'] = null;
        }

        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        $stock = MasterGoldReadyStock::findOrFail((int) decrypt($data['ready_stock_id']));

        if (!$stock->is_active || $stock->status !== 'available') {
            return back()->withErrors(['ready_stock_id' => 'Stok tidak tersedia.']);
        }

        $hargaJualSatuan = $stock->harga_jual_fix ?? $stock->harga_jual_minimal ?? 0.0;
        if ($hargaJualSatuan <= 0) {
            return back()->withErrors(['ready_stock_id' => 'Harga jual item belum diatur.']);
        }

        $shipping = [
            'name'        => $data['shipping_name'],
            'phone'       => $data['shipping_phone'],
            'address'     => $data['shipping_address'],
            'city'        => $data['shipping_city'],
            'province'    => $data['shipping_province'],
            'postal_code' => $data['shipping_postal_code'],
        ];

        $produkId = isset($data['id_master_produk_dan_layanan']) ? (int) $data['id_master_produk_dan_layanan'] : null;
        if (!$produkId) {
            $gramasi = MasterGramasiEmas::where('gramasi', $stock->gramasi)->first();
            if ($gramasi) {
                $produk = MasterProdukDanLayanan::where('id_gramasi', (int) $gramasi->id)
                    ->where('status', 'active')
                    ->first();
                $produkId = $produk?->id ? (int) $produk->id : null;
            }
        }

        $attrs = TransReady::buildAttributesForDraft(
            customerId: (int) $customer->id,
            agenId: $stock->master_agen_id ? (int) $stock->master_agen_id : null,
            produkId: $produkId,
            readyStockId: (int) $stock->id,
            qty: (int) $data['qty'],
            hargaJualSatuan: (float) $hargaJualSatuan,
            deliveryType: $data['delivery_type'],
            shipping: $shipping,
            catatan: $data['catatan'] ?? null
        );
        $attrs['id_master_gold_ready_stock'] = (int) $stock->id;

        $baseInt = (int) floor((float) ($attrs['total_amount'] ?? 0));
        $attempts = 0;
        do {
            $unique = mt_rand(100, 999);
            $attrs['total_amount'] = (float) number_format($baseInt + $unique, 2, '.', '');
            $attempts++;
        } while ($attempts < 5 && TransReady::where('total_amount', $attrs['total_amount'])->exists());

        $ready = TransReady::create($attrs);
        return redirect()
            ->route('customer.ready.show', ['ready' => encrypt((string) $ready->id)])
            ->with('success', 'Transaksi emas ready dibuat, status: pending_payment.');
    }

    public function show(string $ready)
    {
        $id = (int) decrypt($ready);
        $ready = TransReady::findOrFail($id);
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $ready->master_customer_id !== (int) $customer->id) {
            abort(404);
        }

        $paymentLogs = TransPaymentLog::where('ref_type', 'ready')
            ->where('ref_id', $ready->id)
            ->orderByDesc('id')
            ->get();
        $logs = TransReadyLog::where('trans_ready_id', $ready->id)
            ->orderByDesc('id')
            ->get();

        return view('front.customer.ready.show', compact('ready', 'paymentLogs', 'logs'));
    }

    public function confirmPayment(Request $request, string $ready)
    {
        $id = (int) decrypt($ready);
        $ready = TransReady::findOrFail($id);
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $ready->master_customer_id !== (int) $customer->id) {
            abort(404);
        }

        $data = $request->validate([
            'nominal_transfer' => ['required', 'numeric', 'min:0.01'],
            'nama_pengirim'    => ['required', 'string', 'max:150'],
            'bukti_transfer'   => ['required', 'image', 'max:3072'],
        ]);

        $path = $request->file('bukti_transfer')->store('payment_proofs', 'public');

        TransPaymentLog::create([
            'ref_type'        => 'ready',
            'ref_id'          => $ready->id,
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
            ->route('customer.ready.show', ['ready' => encrypt((string) $ready->id)])
            ->with('success', 'Konfirmasi pembayaran terkirim. Menunggu verifikasi agen.');
    }
}