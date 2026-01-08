<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransPo;
use App\Models\MasterGoldPrice;
use App\Models\MasterGoldStock;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransFifoCalculatorController extends Controller
{
    public function index(Request $request): View
    {
        $statuses = array_values(array_filter(array_map('strval', (array) $request->query('statuses', ['paid']))));
        $stockGram = (float) $request->query('stockGram', 0);

        $pos = TransPo::query()
            ->whereIn('status', $statuses)
            ->orderByRaw('COALESCE(paid_at, ordered_at, created_at) ASC')
            ->with(['customer:id,full_name,phone_wa', 'produk.gramasi'])
            ->get(['id', 'kode_po', 'qty', 'total_gram', 'total_amount', 'status', 'ordered_at', 'paid_at', 'created_at', 'master_customer_id', 'shipping_name', 'shipping_phone', 'shipping_address', 'shipping_city', 'shipping_province', 'shipping_postal_code', 'id_master_produk_dan_layanan']);

        $items = [];
        foreach ($pos as $po) {
            $items[] = [
                'id' => (int) $po->id,
                'kode_po' => (string) $po->kode_po,
                'qty' => (int) $po->qty,
                'total_gram' => (float) ($po->total_gram ?? 0.0),
                'total_amount' => (float) ($po->total_amount ?? 0.0),
                'status' => (string) $po->status,
                'ordered_at' => $po->ordered_at,
                'paid_at' => $po->paid_at,
                'created_at' => $po->created_at,
                'customer_name' => optional($po->customer)->full_name,
                'customer_wa' => optional($po->customer)->phone_wa,
                'gramasi' => (float) (optional(optional($po->produk)->gramasi)->gramasi ?? 0.0),
                'shipping_name' => $po->shipping_name,
                'shipping_phone' => $po->shipping_phone,
                'shipping_address' => $po->shipping_address,
                'shipping_city' => $po->shipping_city,
                'shipping_province' => $po->shipping_province,
                'shipping_postal_code' => $po->shipping_postal_code,
            ];
        }

        $calc = self::computeFifoAllocation($items, $stockGram);

        $stockUsed = self::sumTake($calc['allocations']);
        $totalRequired = self::sumItemTotalGram($items);
        $remainingStock = (float) $calc['remaining_stock'];

        $viewMode = (string) $request->query('viewMode', '');
        $fakturs = array_values(array_filter(array_map('strval', (array) $request->query('fakturs', []))));
        $fakturOptions = MasterGoldStock::query()
            ->whereNotNull('no_faktur')
            ->where('status_pengambilan', 'belum_diambil')
            ->orderByDesc('id')
            ->limit(200)
            ->pluck('no_faktur')
            ->unique()
            ->values()
            ->all();

        $pricesByFaktur = [];
        if (!empty($fakturs)) {
            $stocks = MasterGoldStock::query()
                ->whereIn('no_faktur', $fakturs)
                ->get(['id','no_faktur','created_at','gramasi','qty','berat']);

            $stocksByFaktur = [];
            $group = [];
            foreach ($stocks as $s) {
                $fk = (string) ($s->no_faktur ?? '');
                if ($fk !== '') {
                    if (!isset($stocksByFaktur[$fk])) { $stocksByFaktur[$fk] = []; }
                    $stocksByFaktur[$fk][] = [
                        'gramasi' => (float) ($s->gramasi ?? 0.0),
                        'qty' => (int) ($s->qty ?? 0),
                        'berat' => (float) ($s->berat ?? 0.0),
                    ];
                }
                $fk = (string) ($s->no_faktur ?? '');
                if ($fk === '') {
                    continue;
                }
                if (!isset($group[$fk])) {
                    $group[$fk] = [
                        'qty' => 0,
                        'berat' => 0.0,
                        'gramasi_set' => [],
                        'date' => $s->created_at,
                    ];
                }
                $group[$fk]['qty'] += (int) ($s->qty ?? 0);
                $group[$fk]['berat'] += (float) ($s->berat ?? 0.0);
                $g = (float) ($s->gramasi ?? 0.0);
                if ($g > 0) {
                    $group[$fk]['gramasi_set'][(string) number_format($g, 3, '.', '')] = true;
                }
                if ($s->created_at && ($group[$fk]['date'] === null || $s->created_at < $group[$fk]['date'])) {
                    $group[$fk]['date'] = $s->created_at;
                }
            }

            $weightsByFaktur = [];
            foreach ($stocksByFaktur as $fk2 => $rows2) {
                $w = 0.0;
                foreach ($rows2 as $r2) {
                    $g2 = (float) ($r2['gramasi'] ?? 0.0);
                    $q2 = (int) ($r2['qty'] ?? 0);
                    if ($g2 > 0 && $q2 > 0) { $w += ($g2 * $q2); }
                }
                $weightsByFaktur[$fk2] = (float) number_format($w, 3, '.', '');
            }
            foreach ($group as $fk => $agg) {
                $refDate = $agg['date'] ? $agg['date']->format('Y-m-d') : null;
                $price = $refDate
                    ? MasterGoldPrice::where('price_date', '<=', $refDate)
                        ->where('is_active', true)
                        ->orderByDesc('price_date')
                        ->first()
                    : null;
                $gramasiVal = null;
                $keys = array_keys($agg['gramasi_set']);
                if (count($keys) === 1) {
                    $gramasiVal = (float) $keys[0];
                }
                $pricesByFaktur[$fk] = [
                    'price_date' => $price && $price->price_date ? $price->price_date->format('Y-m-d') : null,
                    'source' => (string) ($price->source ?? ''),
                    'price_buy' => (float) ($price->price_buy ?? 0.0),
                    'price_sell' => (float) ($price->price_sell ?? 0.0),
                    'price_buyback' => (float) ($price->price_buyback ?? 0.0),
                    'gramasi' => $gramasiVal,
                    'qty' => (int) ($agg['qty'] ?? 0),
                    'berat' => (float) ($weightsByFaktur[$fk] ?? 0.0),
                ];
            }

            uasort($pricesByFaktur, function ($a, $b) {
                $ga = $a['gramasi'] ?? null;
                $gb = $b['gramasi'] ?? null;
                if ($ga === $gb) return 0;
                if ($ga === null) return 1;
                if ($gb === null) return -1;
                return $ga <=> $gb;
            });

            $resumeQty = 0;
            $resumeBerat = 0.0;
            $gramasiAllSet = [];
            foreach ($group as $fk => $agg) {
                $resumeQty += (int) ($agg['qty'] ?? 0);
                $resumeBerat += (float) ($weightsByFaktur[$fk] ?? 0.0);
                foreach (array_keys($agg['gramasi_set']) as $gk) {
                    $gramasiAllSet[$gk] = true;
                }
            }
            $fakturResume = [
                'count' => (int) count($group),
                'total_qty' => (int) $resumeQty,
                'total_berat' => (float) number_format($resumeBerat, 3, '.', ''),
                'gramasi_unique' => array_values(array_map('floatval', array_keys($gramasiAllSet))),
            ];

            $pricesExpanded = [];
            foreach ($pricesByFaktur as $fk => $p) {
                $g = $p['gramasi'] ?? null;
                $qty = (int) ($p['qty'] ?? 0);
                if ($g === null || $g <= 0 || $qty <= 0) { continue; }
                for ($i = 0; $i < $qty; $i++) {
                    $pricesExpanded[] = [
                        'no_faktur' => (string) $fk,
                        'gramasi' => (float) $g,
                    ];
                }
            }
            $pricesExpandedTotalGram = 0.0;
            $pricesExpandedCountByGram = [
                '1.000' => 0,
                '2.000' => 0,
                '3.000' => 0,
                '5.000' => 0,
                '10.000' => 0,
                '25.000' => 0,
                '50.000' => 0,
                '100.000' => 0,
            ];
            foreach ($pricesExpanded as $row) {
                $g = (float) ($row['gramasi'] ?? 0.0);
                $pricesExpandedTotalGram += $g;
                $key = (string) number_format($g, 3, '.', '');
                if (array_key_exists($key, $pricesExpandedCountByGram)) {
                    $pricesExpandedCountByGram[$key]++;
                }
            }
        } else {
            $fakturResume = [];
            $stocksByFaktur = [];
            $pricesExpanded = [];
            $pricesExpandedTotalGram = 0.0;
            $pricesExpandedCountByGram = [
                '1.000' => 0,
                '2.000' => 0,
                '3.000' => 0,
                '5.000' => 0,
                '10.000' => 0,
                '25.000' => 0,
                '50.000' => 0,
                '100.000' => 0,
            ];
        }

        $groupByGramasi = [];
        foreach ($items as $it) {
            if ((string)($it['status'] ?? '') !== 'paid') { continue; }
            $g = (float) ($it['gramasi'] ?? 0.0);
            $q = (int) ($it['qty'] ?? 0);
            if ($g <= 0 || $q <= 0) { continue; }
            $key = number_format($g, 3, '.', '');
            if (!isset($groupByGramasi[$key])) {
                $groupByGramasi[$key] = ['gramasi' => (float) $key, 'qty' => 0, 'berat' => 0.0];
            }
            $groupByGramasi[$key]['qty'] += $q;
            $groupByGramasi[$key]['berat'] += ($g * $q);
        }
        $poQueueRows = [];
        $poQueueTotalQty = 0;
        $poQueueTotalBerat = 0.0;
        foreach ($groupByGramasi as $row) {
            $poQueueTotalQty += (int) ($row['qty'] ?? 0);
            $poQueueTotalBerat += (float) ($row['berat'] ?? 0.0);
            $poQueueRows[] = [
                'gramasi' => (float) ($row['gramasi'] ?? 0.0),
                'qty' => (int) ($row['qty'] ?? 0),
                'berat' => (float) number_format((float) ($row['berat'] ?? 0.0), 3, '.', ''),
            ];
        }
        $poQueueSummary = [
            'rows' => $poQueueRows,
            'total_qty' => (int) $poQueueTotalQty,
            'total_berat' => (float) number_format($poQueueTotalBerat, 3, '.', ''),
        ];

        $poQueueList = [];
        $thresholdDate = now()->subWeeks(3);
        foreach ($items as $it) {
            if ((string)($it['status'] ?? '') !== 'paid') { continue; }
            $refDate = $it['paid_at'] ?? $it['ordered_at'] ?? $it['created_at'];
            if (!$refDate || $refDate > $thresholdDate) { continue; }
            $qty = max(0, (int) ($it['qty'] ?? 0));
            for ($i = 0; $i < $qty; $i++) {
                $poQueueList[] = [
                    'po_id' => (int) ($it['id'] ?? 0),
                    'created_at' => $it['created_at']  ?? '',
                    'kode_po' => (string) ($it['kode_po'] ?? ''),
                    'customer_name' => (string) ($it['customer_name'] ?? ''),
                    'customer_wa' => (string) ($it['customer_wa'] ?? ''),
                    'gramasi' => (float) ($it['gramasi'] ?? 0.0),
                    'qty' => 1,
                    'total_gram' => (float) ($it['gramasi'] ?? 0.0),
                    'total_amount' => (float) ($it['total_amount'] ?? 0.0),
                ];
            }
        }
        $poCount = (int) count($poQueueList);
        $poQtySum = 0;
        $poBeratSum = 0.0;
        $gramsSet = [];
        foreach ($poQueueList as $r) {
            $poQtySum += (int) ($r['qty'] ?? 0);
            $poBeratSum += (float) ($r['gramasi']*$r['qty'] ?? 0.0);
            $g = (float) ($r['gramasi'] ?? 0.0);
            if ($g > 0) { $gramsSet[(string) number_format($g, 3, '.', '')] = true; }
        }
        $poQueueResume = [
            'count' => (int) $poCount,
            'total_qty' => (int) $poQtySum,
            'total_berat' => (float) number_format($poBeratSum, 3, '.', ''),
            'gramasi_unique' => array_values(array_map('floatval', array_keys($gramsSet))),
        ];

        return view('admin.trans_fifo_calculator.index', [
            'stockGram' => $stockGram,
            'statuses' => $statuses,
            'items' => $items,
            'allocations' => $calc['allocations'],
            'stockUsed' => $stockUsed,
            'remainingStock' => $remainingStock,
            'totalRequired' => $totalRequired,
            'fakturs' => $fakturs,
            'fakturOptions' => $fakturOptions,
            'pricesByFaktur' => $pricesByFaktur,
            'stocksByFaktur' => $stocksByFaktur,
            'pricesExpanded' => $pricesExpanded,
            'pricesExpandedTotalGram' => (float) number_format($pricesExpandedTotalGram, 3, '.', ''),
            'pricesExpandedCounts' => $pricesExpandedCountByGram,
            'fakturResume' => $fakturResume,
            'poQueueSummary' => $poQueueSummary,
            'poQueueList' => $poQueueList,
            'poQueueResume' => $poQueueResume,
            'viewMode' => $viewMode,
        ]);
    }

    private static function computeFifoAllocation(array $items, float $stockGram): array
    {
        $remaining = max(0.0, (float) $stockGram);
        $allocations = [];

        foreach ($items as $it) {
            if ($remaining <= 0.0) {
                break;
            }
            $gramasi = (float) ($it['gramasi'] ?? 0.0);
            $qty = (int) ($it['qty'] ?? 0);
            $need = max(0.0, $gramasi * max(0, $qty));
            $take = $need <= 0.0 ? 0.0 : min($remaining, $need);

            $allocations[] = [
                'po_id' => (int) $it['id'],
                'kode_po' => (string) $it['kode_po'],
                'status' => (string) $it['status'],
                'po_total_gram' => (float) $need,
                
                'ordered_at' => $it['ordered_at'] ?? null,
                'paid_at' => $it['paid_at'] ?? null,
                'created_at' => $it['created_at'] ?? null,
                'name' => (string) ($it['customer_name'] ?? ''),
                'wa' => (string) ($it['customer_wa'] ?? ''),
                'gramasi' => (float) ($it['gramasi'] ?? 0.0),
                'qty' => (int) ($it['qty'] ?? 0),
                'shipping_name' => (string) ($it['shipping_name'] ?? ''),
                'shipping_phone' => (string) ($it['shipping_phone'] ?? ''),
                'shipping_address' => (string) ($it['shipping_address'] ?? ''),
                'shipping_city' => (string) ($it['shipping_city'] ?? ''),
                'shipping_province' => (string) ($it['shipping_province'] ?? ''),
                'shipping_postal_code' => (string) ($it['shipping_postal_code'] ?? ''),
            ];

            $remaining = max(0.0, $remaining - $take);
        }

        return [
            'allocations' => $allocations,
            'remaining_stock' => (float) number_format($remaining, 3, '.', ''),
        ];
    }

    private static function sumTake(array $allocations): float
    {
        $sum = 0.0;
        foreach ($allocations as $row) {
            $sum += (float) ($row['take_gram'] ?? 0.0);
        }
        return (float) number_format($sum, 3, '.', '');
    }

    private static function sumItemTotalGram(array $items): float
    {
        $sum = 0.0;
        foreach ($items as $it) {
            $sum += (float) ($it['total_gram'] ?? 0.0);
        }
        return (float) number_format($sum, 3, '.', '');
    }
}