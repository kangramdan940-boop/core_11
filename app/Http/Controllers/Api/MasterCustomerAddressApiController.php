<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterCustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user || !$user->is_active || $user->role !== 'customer') {
            return response()->json([
                'status' => false,
                'error' => 'Unauthorized',
                'meta' => ['requestId' => (string) Str::uuid()],
            ], 401);
        }

        $payload = [
            'name' => (string) ($request->input('name') ?? ''),
            'phone' => $request->filled('phone') ? (string) $request->input('phone') : null,
            'address' => (string) ($request->input('address') ?? ''),
            'city' => (string) ($request->input('city') ?? ''),
            'tag' => $request->filled('tag') ? (string) $request->input('tag') : null,
            'shipping_cost' => $request->filled('shipping_cost') ? (float) $request->input('shipping_cost') : null,
        ];

        $validator = Validator::make($payload, [
            'name' => ['required','string','max:100'],
            'phone' => ['nullable','string','max:30'],
            'address' => ['required','string','max:255'],
            'city' => ['required','string','max:150'],
            'tag' => ['nullable','string','max:50'],
            'shipping_cost' => ['nullable','numeric','min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'meta' => ['requestId' => (string) Str::uuid()],
            ], 422);
        }

        $data = $validator->validated();

        $addr = new MasterCustomerAddress([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'lines' => [$data['address']],
            'city' => $data['city'],
            'tag' => $data['tag'] ?? null,
            'shipping_cost' => $data['shipping_cost'] ?? null,
            'sys_user_id' => (int) $user->id,
        ]);
        $addr->save();

        return response()->json([
            'status' => true,
            'data' => [
                'id' => (int) $addr->id,
                'name' => (string) $addr->name,
                'phone' => $addr->phone ?: null,
                'lines' => is_array($addr->lines) ? $addr->lines : [],
                'city' => $addr->city ?: null,
                'tag' => $addr->tag ?: null,
                'shippingCost' => $addr->shipping_cost !== null ? (float) $addr->shipping_cost : 0.0,
                'createdAt' => $addr->created_at ? $addr->created_at->toIso8601String() : null,
                'updatedAt' => $addr->updated_at ? $addr->updated_at->toIso8601String() : null,
            ],
            'meta' => ['requestId' => (string) Str::uuid()],
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        if (!$user || !$user->is_active || $user->role !== 'customer') {
            return response()->json([
                'status' => false,
                'error' => 'Unauthorized',
                'meta' => ['requestId' => (string) Str::uuid()],
            ], 401);
        }

        $addr = MasterCustomerAddress::whereKey($id)->where('sys_user_id', $user->id)->first();

        if (!$addr) {
            return response()->json([
                'status' => false,
                'error' => 'Data tidak ditemukan',
                'meta' => [
                    'requestId' => (string) Str::uuid(),
                    'id' => (int) $id,
                ],
            ], 404);
        }

        $addr->delete();

        return response()->json([
            'status' => true,
            'data' => [
                'deleted' => true,
                'id' => (int) $id,
            ],
            'meta' => ['requestId' => (string) Str::uuid()],
        ]);
    }
}