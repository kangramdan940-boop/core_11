<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SysNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();

        $rows = SysNotification::where('sys_user_id', (int) $userId)
            ->orderByDesc('id')
            ->limit(100)
            ->get([
                'id',
                'channel',
                'ref_type',
                'ref_id',
                'title',
                'message',
                'status',
                'is_read',
                'sent_at',
                'read_at',
                'created_at',
            ]);

        $items = $rows->map(function (SysNotification $n) {
            return [
                'id'        => (int) $n->id,
                'channel'   => (string) ($n->channel ?? 'system'),
                'refType'   => $n->ref_type ?: null,
                'refId'     => $n->ref_id !== null ? (int) $n->ref_id : null,
                'title'     => (string) ($n->title ?? ''),
                'message'   => (string) ($n->message ?? ''),
                'status'    => (string) ($n->status ?? 'sent'),
                'isRead'    => (bool) ($n->is_read ?? false),
                'sentAt'    => optional($n->sent_at)->toIso8601String(),
                'readAt'    => optional($n->read_at)->toIso8601String(),
                'createdAt' => optional($n->created_at)->toIso8601String(),
            ];
        })->all();

        return response()->json(['status' => true, 'data' => $items]);
    }
}