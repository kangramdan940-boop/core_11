<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransReady;
use App\Models\TransPaymentLog;
use App\Models\TransReadyLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class TransReadyController extends Controller
{
    public function index(Request $request)
    {
        $status = (string) $request->query('status', '');
        $dateFilter = (string) $request->query('date', '');
        $createdDate = (string) $request->query('created_date', '');

        $query = TransReady::with(['customer', 'agen', 'readyStock'])
            ->orderByDesc('id');

        if ($status !== '') {
            $allowed = ['pending_payment','paid','waiting_shipment','shipped','completed','cancelled'];
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

        $readyTrans = $query->get();

        $countsBase = TransReady::query();
        if ($dateFilter === 'today') {
            $countsBase->whereDate('created_at', now()->toDateString());
        } elseif ($createdDate !== '') {
            $countsBase->whereDate('created_at', $createdDate);
        }
        $totalCount = (clone $countsBase)->count();
        $todayCount = (clone TransReady::query())->whereDate('created_at', now()->toDateString())->count();
        $rawCounts = (clone $countsBase)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt','status')
            ->toArray();
        $statusCounts = [
            'pending_payment'   => (int) ($rawCounts['pending_payment'] ?? 0),
            'paid'              => (int) ($rawCounts['paid'] ?? 0),
            'waiting_shipment'  => (int) ($rawCounts['waiting_shipment'] ?? 0),
            'shipped'           => (int) ($rawCounts['shipped'] ?? 0),
            'completed'         => (int) ($rawCounts['completed'] ?? 0),
            'cancelled'         => (int) ($rawCounts['cancelled'] ?? 0),
        ];

        return view('admin.trans_ready.index', compact('readyTrans', 'statusCounts', 'totalCount', 'todayCount'));
    }

    public function invoiceBulkPdf(Request $request)
    {
        $status = (string) ($request->input('status') ?? 'shipped');
        $since = $request->input('since');
        $until = $request->input('until');

        $query = TransReady::query()->with(['customer','agen','readyStock'])
            ->where('status', $status);
        if ($since) { $query->whereDate('shipped_at', '>=', $since); }
        if ($until) { $query->whereDate('shipped_at', '<=', $until); }
        $items = $query->orderBy('shipped_at')->get();

        $paymentsByReady = TransPaymentLog::where('ref_type', 'ready')
            ->whereIn('ref_id', $items->pluck('id'))
            ->orderByDesc('id')
            ->get()
            ->groupBy('ref_id');

        $pdf = Pdf::loadView('admin.trans_ready.invoice_bulk_pdf', [
            'items' => $items,
            'paymentsByReady' => $paymentsByReady,
            'status' => $status,
            'since' => $since,
            'until' => $until,
        ])->setPaper('a4', 'portrait');

        $filename = 'Invoice-Ready-Bulk-' . $status . '-' . date('Ymd-His') . '.pdf';
        return $pdf->download($filename);
    }

    public function show(TransReady $ready)
    {
        $paymentLogs = TransPaymentLog::where('ref_type', 'ready')
            ->where('ref_id', $ready->id)
            ->orderByDesc('id')
            ->get();
        $logs = TransReadyLog::where('trans_ready_id', $ready->id)
            ->orderByDesc('id')
            ->get();

        return view('admin.trans_ready.show', compact('ready', 'paymentLogs', 'logs'));
    }

    public function updateStatus(Request $request, TransReady $ready)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending_payment','paid','shipped','completed','cancelled'])],
        ]);

        $new = $data['status'];

        if ($ready->status === $new) {
            return redirect()->route('admin.trans.ready.show', $ready)->with('success', 'Status tidak berubah.');
        }

        $ready->status = $new;

        if ($new === 'paid' && !$ready->paid_at) {
            $ready->paid_at = now();
        } elseif ($new === 'shipped' && !$ready->shipped_at) {
            $ready->shipped_at = now();
        } elseif ($new === 'completed' && !$ready->completed_at) {
            $ready->completed_at = now();
        } elseif ($new === 'cancelled' && !$ready->cancelled_at) {
            $ready->cancelled_at = now();
        }

        $ready->save();

        return redirect()->route('admin.trans.ready.show', $ready)->with('success', 'Status transaksi ready diperbarui.');
    }

    public function cancelPendingAll(Request $request)
    {
        $count = 0;
        TransReady::where('status', 'pending_payment')->chunkById(100, function ($items) use (&$count) {
            foreach ($items as $ready) {
                $ready->status = 'cancelled';
                if (!$ready->cancelled_at) {
                    $ready->cancelled_at = now();
                }
                $ready->save();
                TransReadyLog::create([
                    'trans_ready_id' => $ready->id,
                    'status'         => $ready->status,
                    'description'    => 'Transaksi dibatalkan massal pada ' . now(),
                ]);
                $count++;
            }
        });

        return redirect()->route('admin.trans.ready.index')->with('success', 'Berhasil membatalkan ' . $count . ' transaksi pending.');
    }
}