<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\TransPo;
use App\Models\TransReady;
use App\Models\TransKeranjang;
use Illuminate\Support\Facades\DB;

final class FinancialReportController extends Controller
{
    public function index(Request $request): View
    {
        $since = (string) $request->query('since', '');
        $until = (string) $request->query('until', '');

        $poQuery = TransPo::query()->whereNotIn('status', ['pending_payment', 'cancelled']);
        $readyQuery = TransReady::query()->whereIn('status', ['paid', 'shipped', 'completed']);

        if ($since !== '') {
            $poQuery->whereDate('paid_at', '>=', $since);
            $readyQuery->whereDate('paid_at', '>=', $since);
        }
        if ($until !== '') {
            $poQuery->whereDate('paid_at', '<=', $until);
            $readyQuery->whereDate('paid_at', '<=', $until);
        }

        $poItems = $poQuery->get(['id', 'total_amount', 'total_gram', 'shipping_cost', 'status', 'paid_at']);
        $readyItems = $readyQuery->get(['id', 'total_amount', 'shipping_cost', 'status', 'paid_at']);

        $poStatusRows = TransPo::query()
            ->select('status', DB::raw('COUNT(*) as total_trans'), DB::raw('SUM(total_amount) as total_uang'), DB::raw('SUM(total_gram) as total_gram'))
            ->whereNotIn('status', ['pending_payment', 'cancelled'])
            ->when($since !== '', function ($q) use ($since) { $q->whereDate('paid_at', '>=', $since); })
            ->when($until !== '', function ($q) use ($until) { $q->whereDate('paid_at', '<=', $until); })
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $sum = static function ($items, string $field): float {
            $t = 0.0;
            foreach ($items as $item) {
                $t += (float) ($item->{$field} ?? 0.0);
            }
            return (float) number_format($t, 2, '.', '');
        };

        $poPending = (int) TransPo::where('status', 'pending_payment')->count();
        $readyPending = (int) TransReady::where('status', 'pending_payment')->count();
        $keranjangTransferTotal = (float) TransKeranjang::whereNotNull('nominal_transfer')->sum('nominal_transfer');

        return view('admin.reports.index', [
            'filters' => ['since' => $since, 'until' => $until],
            'po_status_rows' => $poStatusRows,
            'po_total_count' => (int) $poQuery->count(),
            'po_total_amount' => $sum($poItems, 'total_amount'),
            'po_total_gram' => (float) number_format((float) $poQuery->sum('total_gram'), 3, '.', ''),
            'po_total_shipping' => $sum($poItems, 'shipping_cost'),
            'ready_total_amount' => $sum($readyItems, 'total_amount'),
            'ready_total_shipping' => $sum($readyItems, 'shipping_cost'),
            'pending_po_count' => $poPending,
            'pending_ready_count' => $readyPending,
            'keranjang_transfer_total' => (float) number_format($keranjangTransferTotal, 2, '.', ''),
        ]);
    }
}