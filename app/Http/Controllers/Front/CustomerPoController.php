<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\TransPo;
use App\Models\MasterCustomer;
use App\Models\TransPoLog;
use App\Models\TransPaymentLog;
use Illuminate\Support\Facades\Storage;

class CustomerPoController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'harga_per_gram'      => ['required', 'numeric', 'min:0'],
            'total_gram'          => ['required', 'numeric', 'min:0.001'],
            'delivery_type'       => ['required', Rule::in(['ship','pickup','titip_agen'])],
            'shipping_name'       => ['nullable', 'string', 'max:150'],
            'shipping_phone'      => ['nullable', 'string', 'max:50'],
            'shipping_address'    => ['nullable', 'string', 'max:255'],
            'shipping_city'       => ['nullable', 'string', 'max:100'],
            'shipping_province'   => ['nullable', 'string', 'max:100'],
            'shipping_postal_code'=> ['nullable', 'string', 'max:10'],
            'catatan'             => ['nullable', 'string'],
        ]);

        if ($data['delivery_type'] !== 'ship') {
            $data['shipping_name'] = null;
            $data['shipping_phone'] = null;
            $data['shipping_address'] = null;
            $data['shipping_city'] = null;
            $data['shipping_province'] = null;
            $data['shipping_postal_code'] = null;
        }

        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();

        $shipping = [
            'name'        => $data['shipping_name'],
            'phone'       => $data['shipping_phone'],
            'address'     => $data['shipping_address'],
            'city'        => $data['shipping_city'],
            'province'    => $data['shipping_province'],
            'postal_code' => $data['shipping_postal_code'],
        ];

        $attrs = TransPo::buildAttributesForDraft(
            customerId: (int) $customer->id,
            agenId: null,
            hargaPerGram: (float) $data['harga_per_gram'],
            totalGram: (float) $data['total_gram'],
            deliveryType: $data['delivery_type'],
            shipping: $shipping,
            catatan: $data['catatan'] ?? null
        );

        $po = $po = TransPo::create($attrs);

        return redirect()
            ->route('customer.po.show', $po)
            ->with('success', 'PO emas berhasil dibuat, status: pending_payment.');
    }

    public function show(TransPo $po)
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $po->master_customer_id !== (int) $customer->id) {
            abort(404);
        }
        $logs = TransPoLog::where('trans_po_id', $po->id)->orderByDesc('id')->get();
        $paymentLogs = TransPaymentLog::where('ref_type', 'po')
            ->where('ref_id', $po->id)
            ->orderByDesc('id')
            ->get();

        return view('front.customer.po.show', compact('po', 'logs', 'paymentLogs'));
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
            ->route('customer.po.show', $po)
            ->with('success', 'Konfirmasi pembayaran terkirim. Menunggu verifikasi agen.');
    }
       
}

