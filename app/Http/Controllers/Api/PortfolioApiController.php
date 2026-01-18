<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterCustomer;
use App\Models\TransPo;
use App\Models\TransReady;
use App\Models\TransCicilan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

final class PortfolioApiController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $customer = MasterCustomer::where('sys_user_id', $userId)->first();
        if (!$customer) {
            return response()->json([
                'status' => false,
                'error' => ['message' => 'Customer tidak ditemukan'],
            ], 404);
        }

        $poGram = (float) TransPo::where('master_customer_id', (int) $customer->id)
            ->whereNotIn('status', ['completed', 'pending_payment', 'cancelled'])
            ->sum('total_gram');

        $readyItems = TransReady::where('master_customer_id', (int) $customer->id)
            ->where('status', 'paid')
            ->with('readyStock')
            ->get();

        $readyGram = 0.0;
        foreach ($readyItems as $r) {
            $gramasi = $r->readyStock && $r->readyStock->gramasi !== null ? (float) $r->readyStock->gramasi : 0.0;
            $qty = (int) ($r->qty ?? 0);
            $readyGram += $gramasi * $qty;
        }

        $cicilanGram = (float) TransCicilan::where('master_customer_id', (int) $customer->id)
            ->where('status', 'active')
            ->sum('gramasi');

        return response()->json([
            'status' => true,
            'data' => [
                'poGram' => $poGram,
                'readyGram' => $readyGram,
                'cicilanGram' => $cicilanGram,
                'totalGram' => $poGram + $readyGram + $cicilanGram,
            ],
            'meta' => [
                'generatedAt' => now()->toIso8601String(),
                'requestId' => (string) Str::uuid(),
            ],
        ]);
    }
}