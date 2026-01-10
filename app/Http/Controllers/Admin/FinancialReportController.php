<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\TransPo;
use App\Models\TransReady;
use App\Models\TransKeranjang;

final class FinancialReportController extends Controller
{
    public function index(Request $request): View
    {
        $since = (string) $request->query('since', '');
        $until = (string) $request->query('until', '');

        $poQuery = TransPo::query()->whereIn('status', ['paid', 'processed', 'ready', 'shipped', 'completed']);
        $readyQuery = TransReady::query()->whereIn('status', ['paid', 'shipped', 'completed']);

        if ($since !== '') {
            $poQuery->whereDate('paid_at', '>=', $since);
            $readyQuery->whereDate('paid_at', '>=', $since);
        }
        if ($until !== '') {
            $poQuery->whereDate('paid_at', '<=', $until);
            $readyQuery->whereDate('paid_at', '<=', $until);
        }

        $poItems = $poQuery->get(['id', 'total_amount', 'shipping_cost', 'status', 'paid_at']);
        $readyItems = $readyQuery->get(['id', 'total_amount', 'shipping_cost', 'status', 'paid_at']);

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
            'po_total_amount' => $sum($poItems, 'total_amount'),
            'po_total_shipping' => $sum($poItems, 'shipping_cost'),
            'ready_total_amount' => $sum($readyItems, 'total_amount'),
            'ready_total_shipping' => $sum($readyItems, 'shipping_cost'),
            'pending_po_count' => $poPending,
            'pending_ready_count' => $readyPending,
            'keranjang_transfer_total' => (float) number_format($keranjangTransferTotal, 2, '.', ''),
        ]);
    }
}