<?php

namespace App\Services;

use App\Models\GoldPrice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GoldPriceService
{
    private ?string $lastError = null;

    public function getLastError(): ?string
    {
        return $this->lastError;
    }
    /**
     * Mengambil data harga emas dari API dan menyimpannya ke database.
     */
    public function fetchAndStoreGoldPrice(): ?GoldPrice
    {
        $this->lastError = null;

        try {
            $goldPriceData = $this->fetchFromHrtagold();

            if ($goldPriceData) {
                return $this->storeGoldPrice($goldPriceData);
            }

            $this->lastError = $this->lastError ?? 'Gagal mengambil data dari sumber';
            return null;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::error('GoldPriceService Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mengambil data harga emas dari hrtagold.id
     */
    private function fetchFromHrtagold(): ?array
    {
        $url = 'https://hrtagold.id/en/gold-price?_rsc=1a28h';
        $ch = curl_init();

        $caBundle = (string) (env('CURL_CA_BUNDLE') ?? '');

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_ENCODING => '',
            CURLOPT_HTTPHEADER => [
                'accept: */*',
                'accept-language: en-US,en;q=0.9',
                'next-router-prefetch: 1',
                'next-router-state-tree: %5B%22%22%2C%7B%7D%2Cnull%2C%22metadata-only%22%5D',
                'next-url: /en',
                'priority: i',
                'referer: https://hrtagold.id/en',
                'rsc: 1',
                'sec-ch-ua: "Google Chrome";v="147", "Not.A/Brand";v="8", "Chromium";v="147"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "macOS"',
                'sec-fetch-dest: empty',
                'sec-fetch-mode: cors',
                'sec-fetch-site: same-origin',
                'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',
            ],
        ];

        if ($caBundle !== '') {
            $options[CURLOPT_CAINFO] = $caBundle;
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $curlErrNo = curl_errno($ch);
        $curlErr = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $httpCode = (int) ($info['http_code'] ?? 0);

        if ($response === false || $curlErrNo !== 0) {
            $this->lastError = 'cURL error (' . $curlErrNo . '): ' . ($curlErr ?: 'unknown');
            return null;
        }

        if ($httpCode >= 400) {
            $this->lastError = 'HTTP ' . $httpCode;
            return null;
        }

        $parsed = $this->parseResponse($response);
        if (!$parsed || empty($parsed['buy_price']) || empty($parsed['buyback_price']) || empty($parsed['price_date'])) {
            $this->lastError = 'Parse gagal: format response berubah atau data tidak lengkap';
            return null;
        }

        return $parsed;
    }

    /**
     * Mengurai respons dari API
     */
    private function parseResponse(string $response): ?array
    {
        $buyPrice = null;
        $sellPrice = null;
        $buybackPrice = null;
        $lastUpdate = null;
        $priceDate = null;

        if (preg_match('/"description","content":"[^"]*Beli:\s*([^|]+)\|\s*Jual\s*([^\.]+)\.\s*Update terakhir\s*([0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4})/u', $response, $matches)) {
            $buyPrice = $this->extractNumericPrice(trim($matches[1]));
            $buybackPrice = $this->extractNumericPrice(trim($matches[2]));
            $lastUpdate = trim($matches[3]);
        }

        if (!$buyPrice && preg_match('/"price\.amount","content":"([^"]+)"/', $response, $matches)) {
            $buyPrice = $this->extractNumericPrice($matches[1]);
        }

        if ((!$buyPrice || !$buybackPrice) && preg_match('/"og:description","content":"Update harga emas terkini - Jual ([^|]+) \| Beli ([^"]+)"/u', $response, $matches)) {
            $buybackPrice = $buybackPrice ?? $this->extractNumericPrice(trim($matches[1]));
            $buyPrice = $buyPrice ?? $this->extractNumericPrice(trim($matches[2]));
        }

        if (!$lastUpdate && preg_match('/Update terakhir\s*([0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4})/u', $response, $matches)) {
            $lastUpdate = trim($matches[1]);
        }

        if (!$priceDate) {
            $priceDate = $this->parsePriceDateFromLastUpdate($lastUpdate);
        }

        if ($buyPrice) {
            $buyPrice += 30000;
        }
        if ($buybackPrice) {
            $buybackPrice += 20000;
        }

        return [
            'buy_price' => $buyPrice,
            'sell_price' => $buybackPrice,
            'buyback_price' => $buybackPrice,
            'last_update' => $lastUpdate,
            'price_date' => $priceDate,
            'raw_response' => $response,
        ];
    }

    /**
     * Mengekstrak nilai numerik dari string harga
     */
    private function extractNumericPrice(string $priceString): int
    {
        $digits = preg_replace('/\D+/', '', $priceString);
        if (!$digits) {
            return 0;
        }

        return (int) $digits;
    }

    private function parsePriceDateFromLastUpdate(?string $lastUpdate): ?string
    {
        if (!$lastUpdate) {
            return null;
        }

        $value = trim($lastUpdate);
        $dt = \DateTime::createFromFormat('j/n/Y', $value) ?: \DateTime::createFromFormat('d/m/Y', $value);
        if ($dt instanceof \DateTime) {
            return $dt->format('Y-m-d');
        }

        if (preg_match('/([0-9]{1,2})\s*\/\s*([0-9]{1,2})\s*\/\s*([0-9]{4})/', $value, $m)) {
            $dt2 = \DateTime::createFromFormat('j/n/Y', $m[1] . '/' . $m[2] . '/' . $m[3]);
            if ($dt2 instanceof \DateTime) {
                return $dt2->format('Y-m-d');
            }
        }

        return null;
    }

    /**
     * Menyimpan data harga emas ke database
     */
    private function storeGoldPrice(array $data): ?GoldPrice
    {
        try {
            DB::beginTransaction();

            $priceDate = $data['price_date'] ?? now()->format('Y-m-d');

            GoldPrice::whereDate('price_date', $priceDate)
                ->update(['is_active' => false]);

            $goldPrice = GoldPrice::create([
                'buy_price' => $data['buy_price'] ?? 0,
                'sell_price' => $data['sell_price'] ?? ($data['buyback_price'] ?? 0),
                'buyback_price' => $data['buyback_price'] ?? 0,
                'source' => 'HRTA Gold',
                'currency' => 'IDR',
                'price_date' => $priceDate,
                'last_updated' => now(),
                'is_active' => true,
                'raw_response' => $data['raw_response'] ?? null,
            ]);

            DB::commit();
            return $goldPrice;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store GoldPrice Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mendapatkan harga emas aktif terbaru
     */
    public function getLatestGoldPrice(): ?GoldPrice
    {
        return GoldPrice::latestActive()->first();
    }

    /**
     * Mendapatkan harga emas untuk tanggal tertentu
     */
    public function getGoldPriceByDate(string $date): ?GoldPrice
    {
        return GoldPrice::onDate($date)->first();
    }
}
