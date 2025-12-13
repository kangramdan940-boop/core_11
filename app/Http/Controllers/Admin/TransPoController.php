<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransPo;
use App\Models\TransPaymentLog;
use App\Models\TransPoLog;
use App\Models\TransPoMobilitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransPoController extends Controller
{
    public function index(Request $request)
    {
        $status = (string) $request->query('status', '');
        $dateFilter = (string) $request->query('date', '');
        $createdDate = (string) $request->query('created_date', '');

        $query = TransPo::with(['customer', 'agen'])
            ->orderByDesc('id');

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
            return $p;
        });

        return view('admin.trans_po.index', compact('pos'));
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

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Status PO diperbarui.');
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
}