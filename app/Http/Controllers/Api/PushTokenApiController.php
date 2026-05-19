<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPushToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PushTokenApiController extends Controller
{
    /**
     * Register atau update Expo push token untuk user yang sedang login.
     * Jika token sudah ada, update device_name & platform dan set is_active = true.
     * Jika belum ada, buat baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'expo_push_token' => ['required', 'string', 'max:255'],
            'device_name'     => ['nullable', 'string', 'max:255'],
            'platform'        => ['nullable', 'string', 'in:ios,android'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $userId = Auth::id();

        $token = UserPushToken::updateOrCreate(
            [
                'sys_user_id'      => $userId,
                'expo_push_token'  => $request->input('expo_push_token'),
            ],
            [
                'device_name' => $request->input('device_name'),
                'platform'    => $request->input('platform'),
                'is_active'   => true,
            ]
        );

        return response()->json([
            'status'  => true,
            'message' => 'Push token berhasil disimpan.',
            'data'    => [
                'id'              => (int) $token->id,
                'expo_push_token' => $token->expo_push_token,
                'device_name'     => $token->device_name,
                'platform'        => $token->platform,
                'is_active'       => (bool) $token->is_active,
                'created_at'      => optional($token->created_at)->toIso8601String(),
                'updated_at'      => optional($token->updated_at)->toIso8601String(),
            ],
        ]);
    }

    /**
     * Hapus / unregister push token (misal saat logout dari device).
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'expo_push_token' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $userId = Auth::id();

        $deleted = UserPushToken::where('sys_user_id', $userId)
            ->where('expo_push_token', $request->input('expo_push_token'))
            ->delete();

        if ($deleted) {
            return response()->json([
                'status'  => true,
                'message' => 'Push token berhasil dihapus.',
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Push token tidak ditemukan.',
        ], 404);
    }
}
