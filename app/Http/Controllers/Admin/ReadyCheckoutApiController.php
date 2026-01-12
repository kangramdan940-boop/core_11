<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\MasterCustomer;
use App\Models\MasterCustomerAddress;
use App\Models\MasterGoldReadyStock;
use App\Models\MasterGramasiEmas;
use App\Models\MasterProdukDanLayanan;
use App\Models\MasterAgen;
use App\Models\TransReady;
use App\Models\TransKeranjang;

class ReadyCheckoutApiController extends Controller
{
    public function checkout(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'readyStockId' => ['required', 'integer', 'exists:master_gold_ready_stock,id'],
            'qty' => ['required', 'integer', 'min:1'],
            'addressId' => ['nullable', 'integer', 'exists:master_customer_address,id'],
            'keranjangId' => ['nullable', 'integer', 'exists:trans_keranjang,id'],
            'deliveryType' => ['nullable', 'string', 'in:ship,pickup'],
            'shippingCost' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'productId' => ['nullable', 'integer', 'exists:master_produk_dan_layanan,id'],
        ]);

        $userId = Auth::id();
        $customer = MasterCustomer::where('sys_user_id', $userId)->firstOrFail();
        $stock = MasterGoldReadyStock::findOrFail((int) $data['readyStockId']);
        if (!$stock->is_active || (string) $stock->status !== 'available') {
            return response()->json(['status' => false, 'error' => 'Stok tidak tersedia'], 404);
        }

        $keranjang = null;
        if (!empty($data['keranjangId'])) {
            $keranjang = TransKeranjang::whereKey((int)$data['keranjangId'])
                ->where('created_by', (int) $userId)
                ->first();
            if (!$keranjang) {
                return response()->json(['status' => false, 'error' => 'Keranjang tidak ditemukan/akses ditolak'], 404);
            }
        }

        if (!$keranjang) {
            if (empty($data['addressId'])) {
                return response()->json(['status' => false, 'error' => 'addressId wajib jika keranjangId tidak diberikan'], 422);
            }
            $address = MasterCustomerAddress::where('id', (int) $data['addressId'])
                ->where('sys_user_id', $userId)
                ->firstOrFail();

            $kodeKeranjang = 'KRG-JE-' . date('Ymd-His') . '-' . Str::upper(Str::random(6));
            $keranjang = TransKeranjang::create([
                'kode_keranjang' => $kodeKeranjang,
                'ongkos_kirim' => (float) ($data['shippingCost'] ?? ($address->shipping_cost ?? 0.0)),
                'id_alamat_pengiriman' => (int) $address->id,
                'created_by' => (int) $userId,
                'expires_at' => now()->addMinutes(30),
                'status_kadaluarsa' => 'active',
                'status_order' => 'perlu_dibayar',
                'catatan' => (string) ($request->input('note') ?? ''),
            ]);
        }

        $addressForShip = $keranjang->alamat;
        $shipping = [
            'name'        => $addressForShip->name ?? null,
            'phone'       => $addressForShip->phone ?? null,
            'address'     => implode(', ', (array) ($addressForShip->lines ?? [])),
            'city'        => $addressForShip->city ?? null,
            'province'    => null,
            'postal_code' => null,
        ];

        $unitPrice = (float) ($stock->harga_jual_fix ?? $stock->harga_jual_minimal ?? 0.0);
        if ($unitPrice <= 0) {
            return response()->json(['status' => false, 'error' => 'Harga jual item belum diatur'], 422);
        }

        $produkId = isset($data['productId']) ? (int) $data['productId'] : null;
        if (!$produkId) {
            $gramasi = MasterGramasiEmas::where('gramasi', $stock->gramasi)->first();
            if ($gramasi) {
                $produk = MasterProdukDanLayanan::where('id_gramasi', (int) $gramasi->id)
                    ->where('status', 'active')
                    ->orderBy('urutan')
                    ->first();
                $produkId = $produk?->id ? (int) $produk->id : null;
            }
        }

        $deliveryType = (string) ($data['deliveryType'] ?? 'ship');
        $agenId = $stock->master_agen_id ? (int) $stock->master_agen_id : null;

        $shippingCost = $keranjang ? 0.0 : (float) ($data['shippingCost'] ?? 0.0);

        $attrs = TransReady::buildAttributesForDraft(
            customerId: (int) $customer->id,
            agenId: $agenId,
            produkId: $produkId,
            readyStockId: (int) $stock->id,
            qty: (int) $data['qty'],
            hargaJualSatuan: (float) $unitPrice,
            deliveryType: $deliveryType,
            shipping: $shipping,
            catatan: $data['note'] ?? null,
            shippingCost: (float) $shippingCost
        );

        $attrs['id_keranjang'] = (int) $keranjang->id;
        if ($agenId) {
            $attrs['rekening_nomor'] = optional(MasterAgen::find($agenId))->rekening_nomor;
        }

        $baseInt = (int) floor((float) ($attrs['total_amount'] ?? 0));
        $attempts = 0;
        do {
            $unique = mt_rand(100, 999);
            $attrs['total_amount'] = (float) number_format($baseInt + $unique, 2, '.', '');
            $attempts++;
        } while ($attempts < 5 && TransReady::where('total_amount', $attrs['total_amount'])->exists());

        $ready = TransReady::create($attrs);

        return response()->json([
            'status' => true,
            'data' => [
                'keranjang' => [
                    'id' => (int) $keranjang->id,
                    'kode_keranjang' => (string) $keranjang->kode_keranjang,
                    'id_alamat_pengiriman' => (int) $keranjang->id_alamat_pengiriman,
                    'ongkos_kirim' => (float) $keranjang->ongkos_kirim,
                    'expires_at' => optional($keranjang->expires_at)->toIso8601String(),
                    'status_kadaluarsa' => (string) $keranjang->status_kadaluarsa,
                    'status_order' => (string) ($keranjang->status_order ?? ''),
                ],
                'ready' => [
                    'id' => (int) $ready->id,
                    'kode_trans' => (string) $ready->kode_trans,
                    'readyStockId' => (int) $ready->master_gold_ready_stock_id,
                    'qty' => (int) $ready->qty,
                    'unitPrice' => (float) $ready->harga_jual_satuan,
                    'totalAmount' => (float) $ready->total_amount,
                    'shippingCost' => (float) $ready->shipping_cost,
                    'status' => (string) $ready->status,
                    'deliveryType' => (string) $ready->delivery_type,
                ],
            ],
        ]);
    }
}