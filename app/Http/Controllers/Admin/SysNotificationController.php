<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SysNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SysNotificationController extends Controller
{
    public function index(Request $request): View
    {
        $channels = ['system', 'email', 'wa', 'sms'];
        $refTypes = ['po', 'ready', 'keranjang', 'cicilan', 'payment', 'other'];
        $statuses = ['pending', 'sent', 'failed'];

        $filters = [
            'channel' => $this->sanitizeChoice($request->string('channel')->toString(), $channels),
            'refType' => $this->sanitizeChoice($request->string('refType')->toString(), $refTypes),
            'status'  => $this->sanitizeChoice($request->string('status')->toString(), $statuses),
            'isRead'  => $request->has('isRead') ? ($request->string('isRead')->toString() === '1' ? '1' : '0') : null,
            'q'       => trim($request->string('q')->toString()),
            'since'   => $request->string('since')->toString(),
            'until'   => $request->string('until')->toString(),
        ];

        $qb = SysNotification::query()->with('user')->orderByDesc('id');

        if ($filters['channel']) { $qb->where('channel', $filters['channel']); }
        if ($filters['refType']) { $qb->where('ref_type', $filters['refType']); }
        if ($filters['status'])  { $qb->where('status', $filters['status']); }
        if ($filters['isRead'] !== null) { $qb->where('is_read', $filters['isRead'] === '1'); }
        if ($filters['q'] !== '') {
            $qb->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%'.$filters['q'].'%')
                  ->orWhere('message', 'like', '%'.$filters['q'].'%');
            });
        }
        if ($filters['since'] !== '') {
            $date = Carbon::parse($filters['since'])->startOfDay();
            $qb->where(function ($q) use ($date) {
                $q->whereDate('sent_at', '>=', $date)
                  ->orWhereDate('created_at', '>=', $date);
            });
        }
        if ($filters['until'] !== '') {
            $date = Carbon::parse($filters['until'])->endOfDay();
            $qb->where(function ($q) use ($date) {
                $q->whereDate('sent_at', '<=', $date)
                  ->orWhereDate('created_at', '<=', $date);
            });
        }

        $items = $qb->limit(500)->get();

        return view('admin.sys_notifications.index', [
            'items'    => $items,
            'channels' => $channels,
            'refTypes' => $refTypes,
            'statuses' => $statuses,
            'filters'  => $filters,
        ]);
    }

    private function sanitizeChoice(?string $value, array $choices): ?string
    {
        if (!$value) { return null; }
        $val = strtolower($value);
        return in_array($val, $choices, true) ? $val : null;
    }
}