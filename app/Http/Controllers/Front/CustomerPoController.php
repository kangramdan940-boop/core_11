<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\TransPo;
use App\Models\MasterCustomer;
use App\Models\MasterProdukDanLayanan;
use App\Models\MasterGramasiEmas;
use App\Models\TransPoLog;
use App\Models\TransPaymentLog;
use Illuminate\Support\Facades\Storage;

class CustomerPoController extends Controller
{
    public function store(Request $request)
    {

        $request['id_master_produk_dan_layanan'] = decrypt($request->id_master_produk_dan_layanan);
        $data = $request->validate([
            'id_master_produk_dan_layanan' => ['required', 'integer', 'exists:master_produk_dan_layanan,id'],
            'shipping_name'                => ['nullable', 'string', 'max:150'],
            'shipping_phone'               => ['nullable', 'string', 'max:50'],
            'shipping_address'             => ['nullable', 'string', 'max:255'],
            'qty'                           => ['required', 'integer', 'min:1'],
            'shipping_city'                => ['nullable', 'string', 'max:100'],
            'shipping_province'            => ['nullable', 'string', 'max:100'],
            'shipping_postal_code'         => ['nullable', 'string', 'max:10'],
            'catatan'                      => ['nullable', 'string'],
        ]);

        $data['delivery_type'] = 'ship';

        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();

        $pendingCount = TransPo::where('master_customer_id', (int) $customer->id)
            ->where('status', 'pending_payment')
            ->count();
        if ($pendingCount >= 2) {
            return back()->withErrors([
                'limit' => 'Maaf, Anda masih memiliki ' . $pendingCount . ' PO yang menunggu pembayaran. Untuk menjaga keteraturan, mohon selesaikan atau batalkan salah satu terlebih dahulu sebelum membuat PO baru. <a href="' . route('customer.all-order') . '">Klik di sini untuk melihat daftar pesanan Anda</a>.'
            ])->withInput();
        }

        $shipping = [
            'name'        => $data['shipping_name'] ?? null,
            'phone'       => $data['shipping_phone'] ?? null,
            'address'     => $data['shipping_address'] ?? null,
            'city'        => $data['shipping_city'] ?? null,
            'province'    => $data['shipping_province'] ?? null,
            'postal_code' => $data['shipping_postal_code'] ?? null,
        ];
        $produk = MasterProdukDanLayanan::findOrFail((int) $data['id_master_produk_dan_layanan']);
        $jasa = (float)$produk->harga_jasa;
        $mgramasi = MasterGramasiEmas::findOrFail((int) $produk->id_gramasi);
        $hargaPerGram = (float) $produk->harga_hariini;

        $attrs = TransPo::buildAttributesForDraft(
            customerId: (int) $customer->id,
            agenId: null,
            produkId: (int) $produk->id,
            jasa: $jasa,
            qty: (float)$data['qty'],
            hargaPerGram: $hargaPerGram,
            totalGram: (float) $mgramasi->gramasi,
            deliveryType: $data['delivery_type'],
            shipping: $shipping,
            catatan: $data['catatan'] ?? null
        );

        $attempts = 0;
        while ($attempts < 5 && TransPo::where('total_amount', $attrs['total_amount'])->exists()) {
            $attrs = TransPo::buildAttributesForDraft(
                customerId: (int) $customer->id,
                agenId: null,
                produkId: (int) $produk->id,
                jasa: $jasa,
                qty: (float)$data['qty'],
                hargaPerGram: $hargaPerGram,
                totalGram: (float) $mgramasi->gramasi,
                deliveryType: $data['delivery_type'],
                shipping: $shipping,
                catatan: $data['catatan'] ?? null
            );
            $attempts++;
        }
        $po = TransPo::create($attrs);
        return redirect()
            ->route('customer.po.show', encrypt($po->id))
            ->with('success', 'PO emas berhasil dibuat, status: pending_payment.');
    }

    public function show(string $po)
    {
        $poId = (int) decrypt($po);
        $poModel = TransPo::findOrFail($poId);

        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $poModel->master_customer_id !== (int) $customer->id) {
            abort(404);
        }
        $logs = TransPoLog::where('trans_po_id', $poModel->id)->orderByDesc('id')->get();
        $paymentLogs = TransPaymentLog::where('ref_type', 'po')
            ->where('ref_id', $poModel->id)
            ->orderByDesc('id')
            ->get();

        return view('front.customer.po.show', ['po' => $poModel, 'logs' => $logs, 'paymentLogs' => $paymentLogs]);
    }

    public function confirmPayment(Request $request, TransPo $po)
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $po->master_customer_id !== (int) $customer->id) {
            abort(404);
        }

        $data = $request->validate([
            'nominal_transfer' => ['required', 'numeric', 'min:0.01'],
            'nama_pengirim'    => ['required', 'string', 'max:150'],
            'bukti_transfer'   => ['required', 'image', 'max:3072'],
        ]);

        $path = $request->file('bukti_transfer')->store('payment_proofs', 'public');

        TransPaymentLog::create([
            'ref_type'        => 'po',
            'ref_id'          => $po->id,
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
            ->route('customer.po.show', encrypt($po->id))
            ->with('success', 'Konfirmasi pembayaran terkirim. Menunggu verifikasi agen.');
    }
       
}

