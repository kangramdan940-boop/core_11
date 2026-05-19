<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserPushToken;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserPushTokenController extends Controller
{
    public function index(Request $request): View
    {
        $query = UserPushToken::query()->with('user')->orderByDesc('id');

        if ($q = trim($request->string('q')->toString())) {
            $query->where(function ($qb) use ($q) {
                $qb->where('expo_push_token', 'like', "%{$q}%")
                    ->orWhere('device_name', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        if ($request->has('is_active') && $request->string('is_active')->toString() !== '') {
            $query->where('is_active', $request->string('is_active')->toString() === '1');
        }

        if ($platform = $request->string('platform')->toString()) {
            $query->where('platform', $platform);
        }

        $items = $query->paginate(20)->withQueryString();

        return view('admin.push_tokens.index', compact('items'));
    }

    public function destroy(UserPushToken $pushToken): RedirectResponse
    {
        $pushToken->delete();

        return redirect()
            ->route('admin.push-tokens.index')
            ->with('status', 'Push token berhasil dihapus.');
    }

    public function toggleActive(UserPushToken $pushToken): RedirectResponse
    {
        $pushToken->update(['is_active' => !$pushToken->is_active]);

        $status = $pushToken->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.push-tokens.index')
            ->with('status', "Push token berhasil {$status}.");
    }

    /**
     * Kirim test notification ke Expo Push API.
     */
    public function sendTestNotification(Request $request): JsonResponse
    {
        $request->validate([
            'expo_push_token' => ['required', 'string'],
            'title'           => ['required', 'string', 'max:255'],
            'body'            => ['required', 'string', 'max:1000'],
            'subtitle'        => ['nullable', 'string', 'max:255'],
            'data_screen'     => ['nullable', 'string', 'max:255'],
            'data_type'       => ['nullable', 'string', 'max:255'],
        ]);

        $payload = [
            'to'        => $request->input('expo_push_token'),
            'title'     => $request->input('title'),
            'body'      => $request->input('body'),
            'sound'     => 'default',
            'badge'     => 1,
            'channelId' => 'default',
            'priority'  => 'high',
        ];

        if ($request->filled('subtitle')) {
            $payload['subtitle'] = $request->input('subtitle');
        }

        $data = [];
        if ($request->filled('data_screen')) {
            $data['screen'] = $request->input('data_screen');
        }
        if ($request->filled('data_type')) {
            $data['type'] = $request->input('data_type');
        }
        if (!empty($data)) {
            $payload['data'] = $data;
        }

        try {
            $response = Http::withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://exp.host/--/api/v2/push/send', $payload);

            $result = $response->json();

            if ($response->successful()) {
                // Update last_used_at
                UserPushToken::where('expo_push_token', $request->input('expo_push_token'))
                    ->update(['last_used_at' => now()]);

                return response()->json([
                    'status'  => true,
                    'message' => 'Notifikasi berhasil dikirim.',
                    'result'  => $result,
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengirim notifikasi.',
                'result'  => $result,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
