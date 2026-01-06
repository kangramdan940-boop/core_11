<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterCustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class MasterCustomerAddressApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user || !$user->is_active || $user->role !== 'customer') {
            return response()->json([
                'status' => false,
                'error' => 'Unauthorized',
                'meta' => ['requestId' => (string) Str::uuid()],
            ], 401);
        }

        $items = MasterCustomerAddress::where('sys_user_id', $user->id)
            ->orderByDesc('id')
            ->get();

        $data = $items->map(function (MasterCustomerAddress $a): array {
            return [
                'id' => (int) $a->id,
                'name' => (string) $a->name,
                'phone' => $a->phone ?: null,
                'lines' => is_array($a->lines) ? $a->lines : [],
                'city' => $a->city ?: null,
                'tag' => $a->tag ?: null,
                'shippingCost' => $a->shipping_cost !== null ? (float) $a->shipping_cost : 0.0,
                'createdAt' => $a->created_at ? $a->created_at->toIso8601String() : null,
                'updatedAt' => $a->updated_at ? $a->updated_at->toIso8601String() : null,
            ];
        })->all();

        return response()->json([
            'status' => true,
            'data' => $data,
            'meta' => [
                'count' => count($data),
                'userId' => (int) $user->id,
                'requestId' => (string) Str::uuid(),
            ],
        ]);
    }
}