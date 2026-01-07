<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterCustomer;
use App\Models\MasterCustomerAddress;
use App\Models\MasterProdukDanLayanan;
use App\Models\TransKeranjang;
use App\Models\TransPo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerCheckoutApiController extends Controller
{
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'addressId' => ['required', 'integer', 'exists:master_customer_address,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:master_produk_dan_layanan,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.unitPrice' => ['required', 'numeric', 'min:0'],
            'items.*.serviceFee' => ['required', 'numeric', 'min:0'],
            'shippingCost' => ['nullable', 'numeric', 'min:0'],
            'grandTotal' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
        ]);

        $userId = Auth::id();
        $customer = MasterCustomer::where('sys_user_id', $userId)->firstOrFail();
        $address = MasterCustomerAddress::where('id', (int) $data['addressId'])->where('sys_user_id', $userId)->firstOrFail();

        $items = collect($data['items']);
        if ($items->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Keranjang kosong'], 422);
        }

        return DB::transaction(function () use ($customer, $address, $items, $userId, $request) {
            $kodeKeranjang = 'KRG-' . date('Ymd-His') . '-' . Str::upper(Str::random(6));
            $keranjang = TransKeranjang::create([
                'kode_keranjang' => $kodeKeranjang,
                'ongkos_kirim' => (float) ($address->shipping_cost ?? 0),
                'id_alamat_pengiriman' => (int) $address->id,
                'created_by' => (int) $userId,
                'expires_at' => now()->addMinutes(30),
                'status_kadaluarsa' => 'active',
                'catatan' => (string) ($request->input('note') ?? ''),
                'status_order' => 'perlu_dibayar',
            ]);

            $shippingTotal = (float) ($keranjang->ongkos_kirim ?? 0);
            $pos = [];
            $first = true;

            foreach ($items as $it) {
                $produk = MasterProdukDanLayanan::find((int) $it['id']);
                $gram = (float) optional($produk?->gramasi)->gramasi;
                if ($gram <= 0) { $gram = 1.0; }
                $qty = (int) $it['qty'];
                $hargaPerGram = (float) ($produk->harga_hariini ?? 0);
                $jasa = (float) ($produk->harga_jasa ?? 0);
                $totalGram = $gram * $qty;
                $poShipping = $first ? $shippingTotal : 0.0;

                $attrs = TransPo::buildAttributesForDraft(
                    (int) $customer->id,
                    null,
                    (int) $produk->id,
                    $hargaPerGram,
                    $jasa,
                    (float) $qty,
                    $totalGram,
                    'ship',
                    [
                        'name' => $address->name ?? null,
                        'phone' => $address->phone ?? null,
                        'address' => implode(', ', (array) ($address->lines ?? [])),
                        'city' => $address->city ?? null,
                        'province' => null,
                        'postal_code' => null,
                    ],
                    null,
                    $poShipping
                );

                $po = TransPo::create(array_merge($attrs, ['id_keranjang' => (int) $keranjang->id]));
                $first = false;

                $pos[] = [
                    'id' => $po->id,
                    'kode_po' => $po->kode_po,
                    'productId' => $produk->id,
                    'qty' => $qty,
                    'totalGram' => $totalGram,
                    'totalAmount' => (float) $po->total_amount,
                    'shippingCost' => (float) $po->shipping_cost,
                ];
            }

            $grandTotal = array_sum(array_map(fn ($p) => (float) $p['totalAmount'], $pos));

            return response()->json([
                'status' => true,
                'data' => [
                    'keranjang' => [
                        'id' => $keranjang->id,
                        'kode_keranjang' => $keranjang->kode_keranjang,
                        'id_alamat_pengiriman' => $keranjang->id_alamat_pengiriman,
                        'ongkos_kirim' => (float) $keranjang->ongkos_kirim,
                        'expires_at' => optional($keranjang->expires_at)->toIso8601String(),
                        'status_kadaluarsa' => $keranjang->status_kadaluarsa,
                        'status_order' => (string) ($keranjang->status_order ?? ''),
                    ],
                    'pos' => $pos,
                    'grandTotal' => $grandTotal,
                ],
            ]);
        });
    }

    public function cart(Request $request, int $id)
    {
        $userId = Auth::id();
        $customer = MasterCustomer::where('sys_user_id', $userId)->firstOrFail();

        $keranjang = TransKeranjang::whereKey($id)->where('created_by', (int) $userId)->first();
        if (!$keranjang) {
            return response()->json(['status' => false, 'error' => 'Keranjang tidak ditemukan'], 404);
        }

        $pos = TransPo::where('id_keranjang', (int) $keranjang->id)
            ->where('master_customer_id', (int) $customer->id)
            ->with('produk.gramasi')
            ->orderBy('id')
            ->get();

        if ($pos->isEmpty()) {
            return response()->json(['status' => false, 'error' => 'Keranjang tidak memiliki PO untuk pengguna ini'], 404);
        }

        $items = $pos->map(function (TransPo $po) {
            $produkId = (int) ($po->id_master_produk_dan_layanan ?? 0);
            $qty = (int) ($po->qty ?? 0);
            $totalGram = (float) ($po->total_gram ?? 0.0);
            return [
                'id' => (int) $po->id,
                'kode_po' => (string) $po->kode_po,
                'productId' => $produkId,
                'gramasi' => (float) optional(optional($po->produk)->gramasi)->gramasi,
                'qty' => $qty,
                'totalGram' => $totalGram,
                'totalAmount' => (float) $po->total_amount,
                'shippingCost' => (float) $po->shipping_cost,
                'status' => (string) ($po->status ?? ''),
            ];
        })->all();

        $grandTotal = array_sum(array_map(fn ($p) => (float) $p['totalAmount'], $items));

        return response()->json([
            'status' => true,
            'data' => [
                'keranjang' => [
                    'id' => (int) $keranjang->id,
                    'kode_keranjang' => (string) $keranjang->kode_keranjang,
                    'id_alamat_pengiriman' => (int) ($keranjang->id_alamat_pengiriman ?? 0),
                    'ongkos_kirim' => (float) ($keranjang->ongkos_kirim ?? 0.0),
                    'expires_at' => optional($keranjang->expires_at)->toIso8601String(),
                    'status_kadaluarsa' => (string) ($keranjang->status_kadaluarsa ?? ''),
                    'status_order' => (string) ($keranjang->status_order ?? ''),
                ],
                'pos' => $items,
                'grandTotal' => $grandTotal,
            ],
        ]);
    }

    public function carts(\Illuminate\Http\Request $request)
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $customer = \App\Models\MasterCustomer::where('sys_user_id', $userId)->firstOrFail();

        $status = (string) $request->query('status', '');
        $allowed = ['perlu_dibayar','dikemas','dikirim','dibatalkan','selesai'];
        $query = \App\Models\TransKeranjang::where('created_by', (int) $userId)->orderByDesc('id');
        if ($status !== '' && in_array($status, $allowed, true)) {
            $query->where('status_order', $status);
        }
        $keranjangs = $query->get();

        $data = $keranjangs->map(function (\App\Models\TransKeranjang $k) use ($customer) {
            $pos = \App\Models\TransPo::where('id_keranjang', (int) $k->id)
                ->where('master_customer_id', (int) $customer->id)
                ->with('produk.gramasi')
                ->orderBy('id')
                ->get();

            $items = $pos->map(function (\App\Models\TransPo $po) {
                $produkId = (int) ($po->id_master_produk_dan_layanan ?? 0);
                $qty = (int) ($po->qty ?? 0);
                $totalGram = (float) ($po->total_gram ?? 0.0);
                return [
                    'id' => (int) $po->id,
                    'kode_po' => (string) $po->kode_po,
                    'productId' => $produkId,
                    'gramasi' => (float) optional(optional($po->produk)->gramasi)->gramasi,
                    'qty' => $qty,
                    'totalGram' => $totalGram,
                    'totalAmount' => (float) $po->total_amount,
                    'shippingCost' => (float) $po->shipping_cost,
                    'status' => (string) ($po->status ?? ''),
                ];
            })->all();

            $grandTotal = array_sum(array_map(fn ($p) => (float) $p['totalAmount'], $items));

            return [
                'keranjang' => [
                    'id' => (int) $k->id,
                    'kode_keranjang' => (string) $k->kode_keranjang,
                    'id_alamat_pengiriman' => (int) ($k->id_alamat_pengiriman ?? 0),
                    'ongkos_kirim' => (float) ($k->ongkos_kirim ?? 0.0),
                    'expires_at' => optional($k->expires_at)->toIso8601String(),
                    'status_kadaluarsa' => (string) ($k->status_kadaluarsa ?? ''),
                    'status_order' => (string) ($k->status_order ?? ''),
                ],
                'pos' => $items,
                'grandTotal' => $grandTotal,
            ];
        })->values()->all();

        return response()->json(['status' => true, 'data' => $data]);
    }

}

