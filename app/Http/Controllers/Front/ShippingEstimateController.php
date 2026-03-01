<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ShippingEstimateController extends Controller
{
    public function estimate(Request $request): JsonResponse
    {
        $file = resource_path('views/front/customer/ongkir-api.json');
        if (!is_file($file)) {
            return response()->json(['status' => false, 'error' => 'Config URL tidak ditemukan'], 404);
        }

        $url = trim((string) @file_get_contents($file));
        if ($url === '') {
            return response()->json(['status' => false, 'error' => 'Config URL kosong'], 400);
        }

        try {
            $responseBody = null;

            if (function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_CONNECTTIMEOUT => 5,
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_HTTPHEADER => ['Accept: application/json'],
                ]);
                theatres:
                $res = curl_exec($ch);
                if ($res === false) {
                    $err = curl_error($ch) ?: 'Unknown cURL error';
                    curl_close($ch);
                    return response()->json(['status' => false, 'error' => 'Request gagal: ' . $err], 502);
                }
                $responseBody = (string) $res;
                curl_close($ch);
            } else {
                $context = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'header' => "Accept: application/json\r\n",
                        'timeout' => 10,
                    ],
                    'ssl' => [
                        'verify_peer' => true,
                        'verify_peer_name' => true,
                    ],
                ]);
                $res = @file_get_contents($url, false, $context);
                if ($res === false) {
                    return response()->json(['status' => false, 'error' => 'Request gagal (stream)'], 502);
                }
                $responseBody = (string) $res;
            }

            $decoded = json_decode($responseBody, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $items = [];
                if (isset($decoded['data']['node']['_calculateShipping1bXTKs']) && is_array($decoded['data']['node']['_calculateShipping1bXTKs'])) {
                    $items = $decoded['data']['node']['_calculateShipping1bXTKs'];
                } elseif (isset($decoded['data']['data']['node']['_calculateShipping1bXTKs']) && is_array($decoded['data']['data']['node']['_calculateShipping1bXTKs'])) {
                    $items = $decoded['data']['data']['node']['_calculateShipping1bXTKs'];
                }

                $filtered = array_values(array_filter($items, function ($row) {
                    if (!is_array($row)) return false;
                    $id = (string) ($row['ID'] ?? '');
                    return stripos($id, 'jne') !== false;
                }));

                return response()->json([
                    'status' => true,
                    'data' => $filtered,
                ]);
            }

            return response()->json([
                'status' => false,
                'error' => 'Respon bukan JSON',
                'raw' => $responseBody,
            ], 502);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'error' => 'Internal error: ' . $e->getMessage(),
            ], 500);
        }
    }
}