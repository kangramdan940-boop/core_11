<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterGoldReadyStock;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class MasterGoldReadyStockApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $this->parseFilters($request);

        $query = MasterGoldReadyStock::with('agen')
            ->where('is_active', true);

        if ($filters['status'] !== null) {
            $query->where('status', $filters['status']);
        }

        $items = $query
            ->orderBy('brand')
            ->orderBy('gramasi')
            ->limit($filters['limit'])
            ->get();

        $data = $items->map(fn (MasterGoldReadyStock $s) => $this->mapStock($s))->all();

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
        $statusRaw = (string) ($request->query('status') ?? 'available');
        $status = $statusRaw !== '' ? $statusRaw : null;

        $limitRaw = (int) ($request->query('limit') ?? 50);
        $limit = max(1, min(200, $limitRaw));

        return [
            'status' => $status,
            'limit' => $limit,
        ];
    }

    private function mapStock(MasterGoldReadyStock $s): array
    {
        return [
            'id' => (int) $s->id,
            'kodeItem' => (string) $s->kode_item,
            'namaProduk' => $s->nama_produk ?? null,
            'brand' => (string) $s->brand,
            'gramasi' => $s->gramasi !== null ? (float) $s->gramasi : null,
            'hargaJualFix' => $s->harga_jual_fix !== null ? (float) $s->harga_jual_fix : null,
            'hargaJualMinimal' => $s->harga_jual_minimal !== null ? (float) $s->harga_jual_minimal : null,
            'kondisiBarang' => (string) $s->kondisi_barang,
            'status' => (string) $s->status,
            'lokasiSimpan' => $s->lokasi_simpan ?? null,
            'deskripsiPengiriman' => $s->deskripsi_pengiriman ?? null,
            'jumlahTerjual' => $s->jumlah_terjual !== null ? (int) $s->jumlah_terjual : null,
            'acara' => $s->acara ?? null,
            'negaraAsal' => $s->negara_asal ?? null,
            'tags' => $s->tags ?? null,
            'isCustom' => (bool) ($s->is_custom ?? false),
            'isMysteryBox' => (bool) ($s->is_mystery_box ?? false),
            'images' => $this->resolveUrls($s->images),
            'videoUrl' => $this->resolveUrl($s->video_url),
            'agen' => [
                'id' => $s->master_agen_id !== null ? (int) $s->master_agen_id : null,
                'name' => optional($s->agen)->name,
            ],
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

    private function resolveUrls($paths): array
    {
        if (!is_array($paths)) return [];
        $out = [];
        foreach ($paths as $p) {
            $url = $this->resolveUrl(is_string($p) ? $p : null);
            if ($url) $out[] = $url;
        }
        return $out;
    }
}