<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransPo;
use App\Models\TransPaymentLog;
use App\Models\TransPoLog;
use App\Models\TransPoMobilitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class TransPoController extends Controller
{
    public function index(Request $request)
    {
        $status = (string) $request->query('status', '');
        $dateFilter = (string) $request->query('date', '');
        $createdDate = (string) $request->query('created_date', '');
        $keranjangId = $request->query('keranjang_id');

        $query = TransPo::with(['customer', 'agen', 'keranjang'])
            ->orderByDesc('id');

        if (!empty($keranjangId) && is_numeric($keranjangId)) {
            $query->where('id_keranjang', (int) $keranjangId);
        }

        if ($status !== '') {
            $allowed = ['pending_payment','paid','processing','ready_at_agen','shipped','completed','cancelled'];
            if (in_array($status, $allowed, true)) {
                $query->where('status', $status);
            }
        }

        if ($dateFilter === 'today') {
            $query->whereDate('created_at', now()->toDateString());
        }

        if ($createdDate !== '') {
            $query->whereDate('created_at', $createdDate);
        }

        $pos = $query->get()->map(function ($p) {
            $waRaw = optional($p->customer)->phone_wa;
            $waDigits = $waRaw ? preg_replace('/\D+/', '', $waRaw) : null;
            if ($waDigits && substr($waDigits, 0, 1) === '0') {
                $waDigits = '62' . substr($waDigits, 1);
            }
            $gramText = number_format((float) ($p->total_gram ?? 0), 3, ',', '.');
            $amountText = number_format((float) ($p->total_amount ?? 0), 2, ',', '.');
            $qtyText = number_format((int) ($p->qty ?? 0), 0, ',', '.');
            $customerName = trim((string) (optional($p->customer)->full_name ?? ''));
            $sapaan = $customerName !== '' ? ('Kak ' . $customerName) : 'Kak';
            $waText = "Assalamu’alaikum " . $sapaan . " 🙏\n\nKami dari jajanemas.com ingin follow up transaksi emas berikut:\n\n📄 Kode Pesanan : " . ($p->kode_po ?? '-') . "\n⚖️ Emas        : " . $gramText . " gram\n📦 Qty         : " . $qtyText . "\n💰 Nominal TF  : Rp " . $amountText . "\n\nApakah transaksi akan dilanjutkan, dibatalkan,\natau ada kendala yang bisa kami bantu?\n\nTerima kasih 🙏\nTim jajanemas.com";
            $p->wa_url = ($p->status === 'pending_payment' && $waDigits)
                ? ('https://wa.me/' . $waDigits . '?text=' . rawurlencode($waText))
                : null;

            $waShipText = "Assalamu’alaikum " . $sapaan . " 🙏\n\nPemberitahuan: Pesanan emas Anda (Kode PO: " . ($p->kode_po ?? '-') . ") telah dikirim.\n\nKurir: " . ($p->resi_courier ?? 'JNE') . " " . ($p->resi_service ?? '') . "\nNomor Resi: " . ($p->resi_number ?? '-') . "\n\nTerima kasih 🙏\nTim jajanemas.com";
            $p->wa_ship_url = ($p->status === 'shipped' && $waDigits && !empty($p->resi_number))
                ? ('https://wa.me/' . $waDigits . '?text=' . rawurlencode($waShipText))
                : null;

            $shipCost = (float) ($p->shipping_cost ?? 0);
            $alamatFormat = "Nama Penerima: " . ($p->shipping_name ?? '-') . "\nNo. HP: " . ($p->shipping_phone ?? '-') . "\nAlamat Lengkap: " . ($p->shipping_address ?? '-') . "\nKota: " . ($p->shipping_city ?? '-') . "\nProvinsi: " . ($p->shipping_province ?? '-') . "\nKode Pos: " . ($p->shipping_postal_code ?? '-');
            if ($shipCost > 0) {
                $shipCostText = number_format($shipCost, 2, ',', '.');
                $waOngkirText = "Assalamu’alaikum " . $sapaan . " 🙏\n\nMohon konfirmasi alamat pengiriman berikut:\n\n" . $alamatFormat . "\n\nTagihan ongkos kirim: Rp " . $shipCostText . ".\nMohon bantuannya untuk pembayaran ongkir. Terima kasih 🙏\nTim jajanemas.com";
            } else {
                $waOngkirText = "Assalamu’alaikum " . $sapaan . " 🙏\n\nMohon kirimkan alamat lengkap pengiriman dengan format berikut:\n\nNama Penerima:\nNo. HP:\nAlamat Lengkap:\nKota:\nProvinsi:\nKode Pos:\n\nSetelah menerima data, kami akan menginformasikan tagihan ongkos kirim. Terima kasih 🙏\nTim jajanemas.com";
            }
            $p->wa_ongkir_url = $waDigits ? ('https://wa.me/' . $waDigits . '?text=' . rawurlencode($waOngkirText)) : null;

            return $p;
        });

        $countsBase = TransPo::query();
        if ($dateFilter === 'today') {
            $countsBase->whereDate('created_at', now()->toDateString());
        } elseif ($createdDate !== '') {
            $countsBase->whereDate('created_at', $createdDate);
        }
        $totalCount = (clone $countsBase)->count();
        $todayCount = (clone TransPo::query())->whereDate('created_at', now()->toDateString())->count();
        $rawCounts = (clone $countsBase)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();
        $statusCounts = [
            'pending_payment' => (int) ($rawCounts['pending_payment'] ?? 0),
            'paid' => (int) ($rawCounts['paid'] ?? 0),
            'processing' => (int) ($rawCounts['processing'] ?? 0),
            'ready_at_agen' => (int) ($rawCounts['ready_at_agen'] ?? 0),
            'shipped' => (int) ($rawCounts['shipped'] ?? 0),
            'completed' => (int) ($rawCounts['completed'] ?? 0),
            'cancelled' => (int) ($rawCounts['cancelled'] ?? 0),
        ];

        return view('admin.trans_po.index', compact('pos', 'statusCounts', 'totalCount', 'todayCount'));
    }

    public function show(TransPo $po)
    {
        $paymentLogs = TransPaymentLog::where('ref_type', 'po')
            ->where('ref_id', $po->id)
            ->orderByDesc('id')
            ->get();

        $mobilities = TransPoMobilitas::where('trans_po_id', $po->id)
            ->orderByDesc('id')
            ->get();

        return view('admin.trans_po.show', compact('po', 'paymentLogs', 'mobilities'));
    }

    public function approvePayment(Request $request, TransPo $po)
    {
        $pending = TransPaymentLog::where('ref_type', 'po')
            ->where('ref_id', $po->id)
            ->where('payment_method', 'manual_transfer')
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->first();

        if (!$pending) {
            return back()->with('error', 'Tidak ada konfirmasi manual pending untuk PO ini.');
        }

        $pending->status = 'paid';
        $pending->paid_at = now();
        $pending->save();

        $po->status = 'paid';
        $po->payment_method = 'manual_transfer';
        $po->payment_reference = $pending->kode_payment;
        $po->paid_at = now();

        if (!$po->estimasi_emas_diterima) {
            $aheadGrams = \App\Models\TransPo::whereIn('status', ['paid','processing'])
                ->where('id', '<>', $po->id)
                ->sum('total_gram');
            $dailyCap = (float) \App\Models\MasterMitraBrankas::where('is_active', true)->sum('harian_limit_gram');
            $extraDays = $dailyCap > 0 ? (int) ceil($aheadGrams / $dailyCap) : 0;
            $baseDate = now()->addWeeks(3);
            $computed = $baseDate->copy()->addDays($extraDays);
            $po->estimasi_emas_diterima = $computed->toDateString();
        }

        $po->save();

        TransPoLog::create([
            'trans_po_id' => $po->id,
            'status'      => $po->status,
            'description' => 'Pembayaran manual disetujui oleh '.($request->user()?->name ?? 'SYSTEM').' pada '.now(),
        ]);

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Pembayaran disetujui dan status PO diperbarui.');
    }

    public function rejectPayment(Request $request, TransPo $po)
    {
        $pending = TransPaymentLog::where('ref_type', 'po')
            ->where('ref_id', $po->id)
            ->where('payment_method', 'manual_transfer')
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->first();

        if (!$pending) {
            return back()->with('error', 'Tidak ada konfirmasi manual pending untuk PO ini.');
        }

        $pending->status = 'failed';
        $pending->failed_at = now();
        $pending->save();

        TransPoLog::create([
            'trans_po_id' => $po->id,
            'status'      => $po->status,
            'description' => 'Pembayaran manual ditolak oleh '.($request->user()?->name ?? 'SYSTEM').' pada '.now(),
        ]);

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Konfirmasi pembayaran ditolak.');
    }

    public function updateStatus(Request $request, TransPo $po)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                'pending_payment','paid','processing','ready_at_agen','shipped','completed','cancelled'
            ])],
        ]);

        $new = $data['status'];

        if ($po->status === $new) {
            return redirect()->route('admin.trans.po.show', $po)->with('success', 'Status tidak berubah.');
        }

        $po->status = $new;

        if ($new === 'paid' && !$po->paid_at) {
            $po->paid_at = now();
        } elseif ($new === 'processing' && !$po->processed_at) {
            $po->processed_at = now();
        } elseif ($new === 'ready_at_agen' && !$po->ready_at_agen_at) {
            $po->ready_at_agen_at = now();
        } elseif ($new === 'shipped' && !$po->shipped_at) {
            $po->shipped_at = now();
        } elseif ($new === 'completed' && !$po->completed_at) {
            $po->completed_at = now();
        } elseif ($new === 'cancelled' && !$po->cancelled_at) {
            $po->cancelled_at = now();
        }

        $po->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status PO diperbarui.',
                'data' => [ 'status' => $po->status ]
            ]);
        }
        return redirect()->back()->with('success', 'Status PO diperbarui.');
    }

    public function updateShipping(Request $request, TransPo $po)
    {
        $data = $request->validate([
            'shipping_name' => ['required', 'string', 'max:150'],
            'shipping_phone' => ['nullable', 'string', 'max:30'],
            'shipping_address' => ['required', 'string'],
            'shipping_city' => ['nullable', 'string', 'max:100'],
            'shipping_province' => ['nullable', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:20'],
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
            'description' => 'Update data pengiriman oleh ' . ($request->user()?->name ?? 'SYSTEM') . ' pada ' . now(),
        ]);

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Data pengiriman diperbarui.');
    }

    public function cancelPendingAll(Request $request)
    {
        $count = 0;
        TransPo::where('status', 'pending_payment')->chunkById(100, function ($items) use (&$count) {
            foreach ($items as $po) {
                $po->status = 'cancelled';
                if (!$po->cancelled_at) {
                    $po->cancelled_at = now();
                }
                $po->save();
                $count++;
            }
        });

        return redirect()->route('admin.trans.po.index')->with('success', 'Berhasil membatalkan ' . $count . ' transaksi pending.');
    }

    public function sendPaidEmail(Request $request, TransPo $po)
    {
        if ($po->status !== 'paid') {
            return back()->withErrors(['email' => 'Email hanya dapat dikirim untuk transaksi berstatus PAID.']);
        }

        $email = trim((string) optional($po->customer)->email);
        if ($email === '') {
            return back()->withErrors(['email' => 'Email pelanggan tidak tersedia.']);
        }

        $subject = 'Konfirmasi Pembayaran PO ' . ($po->kode_po ?? ('PO-' . $po->id));
        $html = view('emails.po_paid', compact('po'))->render();

        try {
            Mail::html($html, function ($message) use ($email, $subject, $po) {
                $message->to($email, (string) (optional($po->customer)->full_name ?? 'Pelanggan'))
                        ->subject($subject);
            });

            \App\Models\EmailLog::create([
                'recipient_email' => $email,
                'recipient_name'  => optional($po->customer)->full_name,
                'subject'         => $subject,
                'status'          => 'success',
                'mail_type'       => 'po_paid',
                'related_type'    => get_class($po),
                'related_id'      => $po->id,
                'user_id'         => auth()->id(),
            ]);

            TransPoLog::create([
                'trans_po_id' => $po->id,
                'status'      => $po->status,
                'description' => 'Email notifikasi pembayaran dikirim ke ' . $email . ' pada ' . now(),
            ]);

            return back()->with('success', 'Email notifikasi pembayaran telah dikirim.');

        } catch (\Exception $e) {
            \App\Models\EmailLog::create([
                'recipient_email' => $email,
                'recipient_name'  => optional($po->customer)->full_name,
                'subject'         => $subject,
                'status'          => 'failed',
                'error_message'   => $e->getMessage(),
                'mail_type'       => 'po_paid',
                'related_type'    => get_class($po),
                'related_id'      => $po->id,
                'user_id'         => auth()->id(),
            ]);

            return back()->withErrors(['email' => 'Gagal mengirim email: ' . $e->getMessage()]);
        }
    }

    public function sendShippingEmail(Request $request, TransPo $po)
    {
        if ($po->status !== 'shipped') {
            return back()->withErrors(['email' => 'Email hanya dapat dikirim untuk transaksi berstatus SHIPPED.']);
        }

        $email = trim((string) optional($po->customer)->email);
        if ($email === '') {
            return back()->withErrors(['email' => 'Email pelanggan tidak tersedia.']);
        }

        $subject = 'Pemberitahuan Pengiriman PO ' . ($po->kode_po ?? ('PO-' . $po->id));
        $html = view('emails.po_shipped', compact('po'))->render();

        try {
            Mail::html($html, function ($message) use ($email, $subject, $po) {
                $message->to($email, (string) (optional($po->customer)->full_name ?? 'Pelanggan'))
                        ->subject($subject);
            });

            \App\Models\EmailLog::create([
                'recipient_email' => $email,
                'recipient_name'  => optional($po->customer)->full_name,
                'subject'         => $subject,
                'status'          => 'success',
                'mail_type'       => 'po_shipped',
                'related_type'    => get_class($po),
                'related_id'      => $po->id,
                'user_id'         => auth()->id(),
            ]);

            TransPoLog::create([
                'trans_po_id' => $po->id,
                'status'      => $po->status,
                'description' => 'Email notifikasi pengiriman dikirim ke ' . $email . ' pada ' . now(),
            ]);

            return back()->with('success', 'Email notifikasi pengiriman telah dikirim.');

        } catch (\Exception $e) {
            \App\Models\EmailLog::create([
                'recipient_email' => $email,
                'recipient_name'  => optional($po->customer)->full_name,
                'subject'         => $subject,
                'status'          => 'failed',
                'error_message'   => $e->getMessage(),
                'mail_type'       => 'po_shipped',
                'related_type'    => get_class($po),
                'related_id'      => $po->id,
                'user_id'         => auth()->id(),
            ]);

            return back()->withErrors(['email' => 'Gagal mengirim email: ' . $e->getMessage()]);
        }
    }

    public function invoicePdf(TransPo $po)
    {
        $paymentLogs = TransPaymentLog::where('ref_type', 'po')
            ->where('ref_id', $po->id)
            ->orderByDesc('id')
            ->get();

        $pdf = Pdf::loadView('admin.trans_po.invoice_pdf', compact('po', 'paymentLogs'))
            ->setPaper('a4', 'landscape');

        $filename = 'Invoice-' . ($po->kode_po ?? ('PO-' . $po->id)) . '.pdf';
        return $pdf->download($filename);
    }

    public function invoice(TransPo $po)
    {
        $paymentLogs = TransPaymentLog::where('ref_type', 'po')
            ->where('ref_id', $po->id)
            ->orderByDesc('id')
            ->get();

        return view('admin.trans_po.invoice', compact('po', 'paymentLogs'));
    }

    public function kwitansiPdf(TransPo $po)
    {
        $paidLog = TransPaymentLog::where('ref_type', 'po')
            ->where('ref_id', $po->id)
            ->where('status', 'paid')
            ->orderByDesc('paid_at')
            ->first();

        $pdf = Pdf::loadView('admin.trans_po.kwitansi_pdf', compact('po', 'paidLog'))
            ->setPaper('a4', 'portrait');

        $filename = 'Kwitansi-' . ($po->kode_po ?? ('PO-' . $po->id)) . '.pdf';
        return $pdf->download($filename);
    }

    public function updateResi(Request $request, TransPo $po)
    {
        $data = $request->validate([
            'resi_number' => ['required', 'string', 'max:100', Rule::unique('trans_po', 'resi_number')->ignore($po->id)],
            'resi_courier' => ['required', 'string', 'max:100'],
            'resi_service' => ['nullable', 'string', 'max:100'],
        ], [
            'resi_number.unique' => 'Nomor resi sudah digunakan. Gunakan nomor lain.',
        ]);

        $resiNormalized = preg_replace('/\s+/', '', (string) $data['resi_number']);

        $exists = TransPo::where('resi_number', $resiNormalized)
            ->where('id', '<>', (int) $po->id)
            ->exists();

        if ($exists) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor resi sudah terdaftar pada transaksi lain.',
                    'errors' => ['resi_number' => ['Nomor resi sudah digunakan.']],
                ], 422);
            }
            return redirect()->back()->withErrors([
                'resi_number' => 'Nomor resi sudah digunakan.',
            ]);
        }

        $po->fill([
            'resi_number' => $resiNormalized,
            'resi_courier' => $data['resi_courier'],
            'resi_service' => $data['resi_service'] ?? null,
        ]);
        $po->save();

        TransPoLog::create([
            'trans_po_id' => $po->id,
            'status' => $po->status,
            'description' => 'Update data resi oleh ' . ($request->user()?->name ?? 'SYSTEM') . ' pada ' . now(),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data resi diperbarui.',
                'data' => [
                    'resi_number' => $po->resi_number,
                    'resi_courier' => $po->resi_courier,
                    'resi_service' => $po->resi_service,
                ],
            ]);
        }
        return redirect()->back()->with('success', 'Data resi diperbarui.');
    }

    public function deliveryNotePdf(TransPo $po)
    {
        $pdf = Pdf::loadView('admin.trans_po.delivery_note_pdf', compact('po'))
            ->setPaper('a4', 'landscape');

        $filename = 'Delivery-Note-' . ($po->kode_po ?? ('PO-' . $po->id)) . '.pdf';
        return $pdf->download($filename);
    }

    public function resiPdf(TransPo $po)
    {
        $pdf = Pdf::loadView('admin.trans_po.resi_pdf', compact('po'))
            ->setPaper('a4', 'landscape');

        $filename = 'Resi-' . ($po->kode_po ?? ('PO-' . $po->id)) . '.pdf';
        return $pdf->download($filename);
    }

    public function manualStore(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'master_customer_id' => ['required','integer','exists:master_customer,id'],
            'id_master_produk_dan_layanan' => ['required','integer','exists:master_produk_dan_layanan,id'],
            'qty' => ['required','integer','min:1'],
        ]);

        $customerId = (int) $data['master_customer_id'];
        $produk = \App\Models\MasterProdukDanLayanan::with('gramasi')->findOrFail((int) $data['id_master_produk_dan_layanan']);
        $jasa = (float) ($produk->harga_jasa ?? 0.0);
        $hargaPerGram = (float) ($produk->harga_hariini ?? 0.0);
        $mgramasi = $produk->gramasi;
        $totalGram = (float) ($mgramasi?->gramasi ?? 0.0);

        $pendingCount = \App\Models\TransPo::where('master_customer_id', $customerId)
            ->where('status','pending_payment')
            ->count();
        if ($pendingCount >= 2) {
            return redirect()->route('admin.trans.po.index')
                ->withErrors(['limit' => 'Customer masih memiliki '. $pendingCount .' PO pending_payment. Selesaikan atau batalkan terlebih dahulu.']);
        }

        $attrs = \App\Models\TransPo::buildAttributesForDraft(
            customerId: $customerId,
            agenId: null,
            produkId: (int) $produk->id,
            hargaPerGram: $hargaPerGram,
            jasa: $jasa,
            qty: (float) $data['qty'],
            totalGram: $totalGram,
            deliveryType: 'ship',
            shipping: [],
            catatan: null,
            shippingCost: 0.0
        );

        $attempts = 0;
        while ($attempts < 5 && \App\Models\TransPo::where('total_amount', $attrs['total_amount'])->exists()) {
            $attrs = \App\Models\TransPo::buildAttributesForDraft(
                customerId: $customerId,
                agenId: null,
                produkId: (int) $produk->id,
                hargaPerGram: $hargaPerGram,
                jasa: $jasa,
                qty: (float) $data['qty'],
                totalGram: $totalGram,
                deliveryType: 'ship',
                shipping: [],
                catatan: null,
                shippingCost: 0.0
            );
            $attempts++;
        }

        $po = \App\Models\TransPo::create($attrs);

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'PO emas berhasil dibuat untuk customer ini.');
    }

    public function paidToProcessingOlderTwoDays(Request $request)
    {
        $threshold = now()->subDays(2);
        $count = 0;
        TransPo::where('status', 'paid')
            ->whereNotNull('paid_at')
            ->where('paid_at', '<=', $threshold)
            ->chunkById(100, function ($items) use (&$count) {
                foreach ($items as $po) {
                    $po->status = 'processing';
                    if (!$po->processed_at) {
                        $po->processed_at = now();
                    }
                    $po->save();
                    $count++;
                }
            });
        return redirect()->route('admin.trans.po.index')->with('success', 'Berhasil mengubah ' . $count . ' transaksi PAID (>2 hari) menjadi PROCESSING.');
    }
}