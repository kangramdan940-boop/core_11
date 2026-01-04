<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterProdukDanLayanan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class MasterProdukDanLayananApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $this->parseFilters($request);

        $query = MasterProdukDanLayanan::with('gramasi');

        if ($filters['status'] !== null) {
            $query->where('status', $filters['status']);
        }
        if ($filters['type'] === 'ready') {
            $query->where('is_allow_ready', true);
        } elseif ($filters['type'] === 'po') {
            $query->where('is_allow_po', true);
        }

        $items = $query->orderBy('urutan')->limit($filters['limit'])->get();

        $data = $items->map(fn (MasterProdukDanLayanan $p) => $this->mapProduk($p))->all();

        return response()->json([
            'status' => true,
            'data' => $data,
            'meta' => [
                'count' => count($data),
                'filters' => $filters,
                'requestId' => (string) Str::uuid(),
            ],
        ]);
    }

    private function parseFilters(Request $request): array
    {
        $typeRaw = (string) ($request->query('type') ?? '');
        $type = in_array($typeRaw, ['ready', 'po'], true) ? $typeRaw : null;

        $statusRaw = (string) ($request->query('status') ?? 'active');
        $status = $statusRaw !== '' ? $statusRaw : null;

        $limitRaw = (int) ($request->query('limit') ?? 50);
        $limit = max(1, min(200, $limitRaw));

        return [
            'type' => $type,
            'status' => $status,
            'limit' => $limit,
        ];
    }

    private function mapProduk(MasterProdukDanLayanan $p): array
    {
        $g = $p->gramasi;

        return [
            'id' => (int) $p->id,
            'gramasi' => [
                'id' => $g?->id ? (int) $g->id : null,
                'nama' => $g?->nama ?? null,
                'gramasi' => $g?->gramasi !== null ? (float) $g->gramasi : null,
            ],
            'hargaHariIni' => $p->harga_hariini !== null ? (float) $p->harga_hariini : null,
            'isAllowReady' => (bool) $p->is_allow_ready,
            'isAllowPo' => (bool) $p->is_allow_po,
            'hargaJasa' => $p->harga_jasa !== null ? (float) $p->harga_jasa : null,
            'imageUrl' => $this->resolveUrl($p->image_produk),
            'expiredDate' => $p->expired_dae ? $p->expired_dae->toDateString() : null,
            'urutan' => $p->urutan !== null ? (int) $p->urutan : null,
            'status' => (string) $p->status,
        ];
    }

    private function resolveUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }
        return asset(ltrim($path, '/'));
    }
}