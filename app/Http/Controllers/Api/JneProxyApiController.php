<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class JneProxyApiController extends Controller
{
    public function cities(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user || !$user->is_active || $user->role !== 'customer') {
            return response()->json(['status' => false, 'error' => 'Unauthorized', 'meta' => ['requestId' => (string) Str::uuid()]], 401);
        }

        $search = (string) ($request->query('search') ?? '');
        if (mb_strlen($search) < 3) {
            return response()->json(['status' => true, 'data' => [], 'meta' => ['requestId' => (string) Str::uuid()]]);
        }

        $url = 'https://www.jne.co.id/api-destination?search=' . urlencode($search);
        try {
            $ctx = stream_context_create([
                "ssl" => ["verify_peer" => false, "verify_peer_name" => false],
                "http" => ["header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"]
            ]);
            $raw = @file_get_contents($url, false, $ctx);
            if ($raw === false) {
                return response()->json(['status' => true, 'data' => [], 'meta' => ['requestId' => (string) Str::uuid()]]);
            }
            $json = json_decode($raw, true);
            $items = [];
            if (is_array($json)) {
                $payload = [];
                if (isset($json['data']) && is_array($json['data'])) {
                    $payload = $json['data'];
                } elseif (isset($json[0]) && is_array($json[0])) {
                    $payload = $json;
                }
                foreach ($payload as $it) {
                    $code = isset($it['code']) ? (string) $it['code'] : null;
                    $label = isset($it['label']) ? (string) $it['label'] : null;
                    if ($code && $label) $items[] = ['code' => $code, 'label' => $label];
                }
            }
            return response()->json(['status' => true, 'data' => $items, 'meta' => ['requestId' => (string) Str::uuid()]]);
        } catch (\Throwable $e) {
            return response()->json(['status' => true, 'data' => [], 'meta' => ['requestId' => (string) Str::uuid()]]);
        }
    }

    public function shippingFee(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user || !$user->is_active || $user->role !== 'customer') {
            return response()->json(['status' => false, 'error' => 'Unauthorized', 'meta' => ['requestId' => (string) Str::uuid()]], 401);
        }

        $destination = (string) ($request->query('destination') ?? '');
        if ($destination === '') {
            return response()->json(['status' => false, 'error' => 'Destination is required', 'meta' => ['requestId' => (string) Str::uuid()]], 422);
        }

        $origin = 'BKI10000';
        $weight = 1;
        $url = 'https://www.jne.co.id/shipping-fee?origin=' . $origin . '&destination=' . urlencode($destination) . '&weight=' . $weight;

        try {
            $ctx = stream_context_create([
                "ssl" => ["verify_peer" => false, "verify_peer_name" => false],
                "http" => ["header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"]
            ]);
            $html = @file_get_contents($url, false, $ctx);
            if ($html === false) {
                return response()->json(['status' => true, 'data' => [], 'meta' => ['requestId' => (string) Str::uuid()]]);
            }

            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            $xpath = new \DOMXPath($dom);
            $rows = $xpath->query('//table//tr');

            $out = [];
            if ($rows instanceof \DOMNodeList && $rows->length > 0) {
                foreach ($rows as $row) {
                    $cells = $row->getElementsByTagName('td');
                    if ($cells->length < 2) {
                        continue;
                    }

                    $service = trim($cells->item(0)->textContent);

                    $textAll = '';
                    for ($i = 0; $i < $cells->length; $i++) {
                        $textAll .= ' ' . trim($cells->item($i)->textContent);
                    }

                    $price = null;
                    if (preg_match('/Rp\s*([0-9\.,]+)/', $textAll, $m)) {
                        $price = floatval(str_replace(['.', ','], ['', '.'], $m[1]));
                    }

                    $etd = null;
                    if (preg_match('/(ETD|Estimasi)[^0-9]*(\d+\s*[-–]\s*\d+|\d+)\s*hari/i', $textAll, $em)) {
                        $etd = $em[0];
                    }

                    $label = $service !== ''
                        ? $service . ($price !== null ? ' - Rp ' . number_format($price, 0, ',', '.') : '')
                        : trim($textAll);

                    $out[] = ['service' => $service, 'label' => $label, 'price' => $price, 'etd' => $etd];
                }
            }

            return response()->json(['status' => true, 'data' => $out, 'meta' => ['requestId' => (string) Str::uuid()]]);
        } catch (\Throwable $e) {
            return response()->json(['status' => true, 'data' => [], 'meta' => ['requestId' => (string) Str::uuid()]]);
        }
    }
}