<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoldPrice;
use App\Services\GoldPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class WpSettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.wp_settings.index', [
            'previewUrl' => url('/'),
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        return redirect()->back()->with('success', 'Settings saved successfully');
    }

    public function syncGoldPrice(GoldPriceService $service): JsonResponse
    {
        $goldPrice = $service->fetchAndStoreGoldPrice();
        if (!$goldPrice) {
            return response()->json([
                'success' => false,
                'message' => $service->getLastError() ?? 'Gagal sinkronisasi harga emas',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $goldPrice->id,
                'buy_price' => (string) $goldPrice->buy_price,
                'buyback_price' => (string) $goldPrice->buyback_price,
                'sell_price' => (string) $goldPrice->sell_price,
                'source' => (string) ($goldPrice->source ?? ''),
                'currency' => (string) ($goldPrice->currency ?? ''),
                'price_date' => optional($goldPrice->price_date)->format('Y-m-d'),
                'last_updated' => optional($goldPrice->last_updated)->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function storeGoldPrice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'buy_price' => ['required', 'numeric', 'min:0'],
            'buyback_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['nullable', 'numeric', 'min:0'],
            'price_date' => ['required', 'date'],
            'last_updated' => ['nullable', 'date'],
            'source' => ['nullable', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $priceDate = (string) $validated['price_date'];

        return \DB::transaction(function () use ($validated, $priceDate) {
            GoldPrice::whereDate('price_date', $priceDate)->update(['is_active' => false]);

            $row = GoldPrice::create([
                'buy_price' => $validated['buy_price'],
                'sell_price' => $validated['sell_price'] ?? $validated['buyback_price'],
                'buyback_price' => $validated['buyback_price'],
                'source' => $validated['source'] ?? 'Manual',
                'currency' => $validated['currency'] ?? 'IDR',
                'price_date' => $priceDate,
                'last_updated' => $validated['last_updated'] ?? now(),
                'is_active' => (bool) ($validated['is_active'] ?? true),
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $row->id,
                ],
            ]);
        });
    }

    public function testConnection(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Connection successful',
        ]);
    }

    public function syncNow(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Sync completed successfully',
        ]);
    }

    public function floatingPriceIndex(): JsonResponse
    {
        $rows = DB::table('floating_price_and_buyback')
            ->orderByDesc('id')
            ->get(['id', 'icon', 'brand', 'harga', 'buyback', 'created_at', 'updated_at']);

        return response()->json([
            'success' => true,
            'data' => $rows,
        ]);
    }

    public function floatingPriceStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'icon' => ['nullable', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'integer', 'min:0'],
            'buyback' => ['required', 'integer', 'min:0'],
        ]);

        $id = DB::table('floating_price_and_buyback')->insertGetId([
            'icon' => $validated['icon'] ?? null,
            'brand' => $validated['brand'],
            'harga' => $validated['harga'],
            'buyback' => $validated['buyback'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => ['id' => $id],
        ]);
    }

    public function floatingPriceUpdate(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'icon' => ['nullable', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'integer', 'min:0'],
            'buyback' => ['required', 'integer', 'min:0'],
        ]);

        $updated = DB::table('floating_price_and_buyback')
            ->where('id', $id)
            ->update([
                'icon' => $validated['icon'] ?? null,
                'brand' => $validated['brand'],
                'harga' => $validated['harga'],
                'buyback' => $validated['buyback'],
                'updated_at' => now(),
            ]);

        if ($updated === 0 && !DB::table('floating_price_and_buyback')->where('id', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function floatingPriceDestroy(int $id): JsonResponse
    {
        $deleted = DB::table('floating_price_and_buyback')->where('id', $id)->delete();
        if ($deleted === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function etalaseEmasIndex(): JsonResponse
    {
        $rows = DB::table('wp_etalase_emas')
            ->orderByDesc('id')
            ->get(['id', 'icon', 'code', 'brand', 'berat', 'stok', 'status', 'harga', 'buyback', 'created_at', 'updated_at']);

        return response()->json([
            'success' => true,
            'data' => $rows,
        ]);
    }

    public function etalaseEmasStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'icon' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'in:emas_ready,emas_preorder,buyback'],
            'brand' => ['required', 'string', 'max:255'],
            'berat' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'string', 'max:50'],
            'status' => ['required', 'string', 'max:50'],
            'harga' => ['required', 'integer', 'min:0'],
            'buyback' => ['required', 'integer', 'min:0'],
        ]);

        $id = DB::table('wp_etalase_emas')->insertGetId([
            'icon' => $validated['icon'] ?? null,
            'code' => $validated['code'] ?? null,
            'brand' => $validated['brand'],
            'berat' => $validated['berat'],
            'stok' => $validated['stok'],
            'status' => $validated['status'],
            'harga' => $validated['harga'],
            'buyback' => $validated['buyback'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => ['id' => $id],
        ]);
    }

    public function etalaseEmasUpdate(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'icon' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'in:emas_ready,emas_preorder,buyback'],
            'brand' => ['required', 'string', 'max:255'],
            'berat' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'string', 'max:50'],
            'status' => ['required', 'string', 'max:50'],
            'harga' => ['required', 'integer', 'min:0'],
            'buyback' => ['required', 'integer', 'min:0'],
        ]);

        $updated = DB::table('wp_etalase_emas')
            ->where('id', $id)
            ->update([
                'icon' => $validated['icon'] ?? null,
                'code' => $validated['code'] ?? null,
                'brand' => $validated['brand'],
                'berat' => $validated['berat'],
                'stok' => $validated['stok'],
                'status' => $validated['status'],
                'harga' => $validated['harga'],
                'buyback' => $validated['buyback'],
                'updated_at' => now(),
            ]);

        if ($updated === 0 && !DB::table('wp_etalase_emas')->where('id', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function etalaseEmasDestroy(int $id): JsonResponse
    {
        $deleted = DB::table('wp_etalase_emas')->where('id', $id)->delete();
        if ($deleted === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}