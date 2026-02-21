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
use Illuminate\Support\Facades\Mail;
use App\Models\MasterAgen;

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
            'shipping_cost'                => ['nullable', 'numeric', 'min:0'],
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
        $shippingCost = (float) ($data['shipping_cost'] ?? 0);

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
            catatan: $data['catatan'] ?? null,
            shippingCost: $shippingCost
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
                catatan: $data['catatan'] ?? null,
                shippingCost: $shippingCost
            );
            $attempts++;
        }
        $selectedAgenId = $attrs['master_agen_id'] ?? null;
        if ($selectedAgenId) {
            $attrs['rekening_nomor'] = optional(MasterAgen::find((int) $selectedAgenId))->rekening_nomor;
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

        $dir = public_path('uploads/payment_proofs');
        \Illuminate\Support\Facades\File::ensureDirectoryExists($dir);
        $file = $request->file('bukti_transfer');
        $filename = uniqid('proof_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);
        $path = 'uploads/payment_proofs/' . $filename;

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

    public function notifyTransfer(Request $request, TransPo $po)
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $po->master_customer_id !== (int) $customer->id) {
            abort(404);
        }
        if ($po->status !== 'pending_payment') {
            return back()->withErrors(['status' => 'Notifikasi hanya untuk transaksi berstatus pending_payment.']);
        }
        if ($po->notify_transfer_sent_at) {
            return redirect()->route('customer.po.show', encrypt($po->id))->with('error', 'Notifikasi email sudah dikirim untuk transaksi ini.');
        }
        // $email = trim((string) optional($po->agen)->email);
        $email = 'wfirdausi08@gmail.com';
        if ($email === '') {
            return back()->withErrors(['email' => 'Email agen tidak tersedia.']);
        }
        $amountDisplay = 'Rp ' . number_format((float)$po->total_amount, 0, ',', '.');
        $subject = '[' . $amountDisplay . '] Konfirmasi Customer Sudah Transfer - ' . ($po->kode_po ?? ('PO-' . $po->id));
        $html = view('emails.po_transfer_notify', compact('po'))->render();
        try {
            Mail::html($html, function ($message) use ($email, $subject, $po) {
                $message->to($email, (string) (optional($po->agen)->name ?? 'Agen'))
                        ->subject($subject);
            });

            \App\Models\EmailLog::create([
                'recipient_email' => $email,
                'recipient_name'  => optional($po->agen)->name,
                'subject'         => $subject,
                'status'          => 'success',
                'mail_type'       => 'po_transfer_notify',
                'related_type'    => get_class($po),
                'related_id'      => $po->id,
                'user_id'         => auth()->id(),
            ]);

            $po->notify_transfer_sent_at = now();
            $po->save();

            TransPoLog::create([
                'trans_po_id' => $po->id,
                'status'      => $po->status,
                'description' => 'Customer mengirim notifikasi sudah transfer (tanpa bukti) ke agen pada ' . now(),
            ]);

            return redirect()
                ->route('customer.po.show', encrypt($po->id))
                ->with('success', 'Notifikasi email dikirim ke agen.');
        } catch (\Exception $e) {
            \App\Models\EmailLog::create([
                'recipient_email' => $email,
                'recipient_name'  => optional($po->agen)->name,
                'subject'         => $subject,
                'status'          => 'failed',
                'error_message'   => $e->getMessage(),
                'mail_type'       => 'po_transfer_notify',
                'related_type'    => get_class($po),
                'related_id'      => $po->id,
                'user_id'         => auth()->id(),
            ]);

            return back()->withErrors(['email' => 'Gagal mengirim email: ' . $e->getMessage()]);
        }
    }

    public function confirmReceived(Request $request, TransPo $po)
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $po->master_customer_id !== (int) $customer->id) {
            abort(404);
        }
        if ($po->status !== 'shipped') {
            return back()->withErrors(['status' => 'Konfirmasi hanya untuk transaksi berstatus shipped.']);
        }
        $po->status = 'completed';
        $po->completed_at = now();
        $po->save();

        TransPoLog::create([
            'trans_po_id' => $po->id,
            'status'      => $po->status,
            'description' => 'Customer mengonfirmasi barang sudah diterima pada ' . now(),
        ]);

        return redirect()
            ->route('customer.po.show', encrypt($po->id))
            ->with('success', 'Terima kasih, barang dinyatakan sudah diterima. Transaksi ditandai selesai.');
    }

    public function updateShipping(Request $request, TransPo $po)
    {
        $customer = MasterCustomer::where('sys_user_id', Auth::id())->firstOrFail();
        if ((int) $po->master_customer_id !== (int) $customer->id) {
            abort(404);
        }
        if ($po->status !== 'shipped') {
            return back()->withErrors(['status' => 'Pengisian alamat hanya tersedia untuk transaksi berstatus shipped.']);
        }
        $data = $request->validate([
            'shipping_name' => ['required', 'string', 'max:150'],
            'shipping_phone' => ['nullable', 'string', 'max:50'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['nullable', 'string', 'max:100'],
            'shipping_province' => ['nullable', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:10'],
        ]);
        $po->fill([
            'shipping_name' => $data['shipping_name'],
            'shipping_phone' => $data['shipping_phone'] ?? null,
            'shipping_address' => $data['shipping_address'],
            'shipping_city' => $data['shipping_city'] ?? null,
            'shipping_province' => $data['shipping_province'] ?? null,
            'shipping_postal_code' => $data['shipping_postal_code'] ?? null,
        ]);
        $po->save();
        TransPoLog::create([
            'trans_po_id' => $po->id,
            'status' => $po->status,
            'description' => 'Customer memperbarui data pengiriman pada ' . now(),
        ]);
        return redirect()->route('customer.po.show', encrypt($po->id))->with('success', 'Data pengiriman berhasil diperbarui.');
    }
}

