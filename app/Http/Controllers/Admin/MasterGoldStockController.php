<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterGoldStock;
use App\Models\MasterMitraBrankas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class MasterGoldStockController extends Controller
{
    public function index()
    {
        $stocks = MasterGoldStock::with('mitra')->orderByDesc('id')->get();
        return view('admin.master_gold_stock.index', compact('stocks'));
    }

    public function create()
    {
        $mitras = MasterMitraBrankas::orderBy('nama_lengkap')->get(['id','nama_lengkap','kode_mitra']);
        return view('admin.master_gold_stock.create', compact('mitras'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'master_mitra_brankas_id' => ['nullable', 'integer', 'exists:master_mitra_brankas,id'],
            'gramasi'                 => ['required', 'numeric', 'min:0'],
            'qty'                     => ['required', 'integer', 'min:0'],
            'no_faktur'               => ['nullable', 'string', 'max:100'],
            'uraian'                  => ['nullable', 'string'],
            'berat'                   => ['required', 'numeric', 'min:0'],
            'harga'                   => ['required', 'numeric', 'min:0'],
            'total_pembayaran'        => ['required', 'numeric', 'min:0'],
            'uang_modal_mitra'        => ['nullable', 'numeric', 'min:0'],
            'uang_ganti_jajan_emas'   => ['nullable', 'numeric', 'min:0'],
            'uang_komisi_mitra'       => ['nullable', 'numeric', 'min:0'],
            'total_komisi'            => ['nullable', 'numeric', 'min:0'],
            'status_pengambilan'      => ['nullable', 'string', 'in:belum_diambil,sudah_diambil'],
            'file_faktur'             => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:51200'],
            'file_faktur_url'         => ['nullable', 'string', 'max:255'],
            'struk_komisi'            => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:51200'],
            'struk_komisi_url'        => ['nullable', 'string', 'max:255'],
            'struk_bayar_mitra'       => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:51200'],
            'struk_bayar_mitra_url'   => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('file_faktur')) {
            $dir = public_path('uploads/gold_stocks/faktur');
            File::ensureDirectoryExists($dir);
            $file = $request->file('file_faktur');
            $ext = strtolower($file->getClientOriginalExtension() ?: '');
            $filename = uniqid('faktur_', true) . ($ext ? '.' . $ext : '');
            $file->move($dir, $filename);
            $data['file_faktur_url'] = 'uploads/gold_stocks/faktur/' . $filename;
        }
        if ($request->hasFile('struk_komisi')) {
            $dir = public_path('uploads/gold_stocks/struk_komisi');
            File::ensureDirectoryExists($dir);
            $file = $request->file('struk_komisi');
            $ext = strtolower($file->getClientOriginalExtension() ?: '');
            $filename = uniqid('struk_komisi_', true) . ($ext ? '.' . $ext : '');
            $file->move($dir, $filename);
            $data['struk_komisi_url'] = 'uploads/gold_stocks/struk_komisi/' . $filename;
        }
        if ($request->hasFile('struk_bayar_mitra')) {
            $dir = public_path('uploads/gold_stocks/struk_bayar_mitra');
            File::ensureDirectoryExists($dir);
            $file = $request->file('struk_bayar_mitra');
            $ext = strtolower($file->getClientOriginalExtension() ?: '');
            $filename = uniqid('struk_bayar_mitra_', true) . ($ext ? '.' . $ext : '');
            $file->move($dir, $filename);
            $data['struk_bayar_mitra_url'] = 'uploads/gold_stocks/struk_bayar_mitra/' . $filename;
        }

        $stock = MasterGoldStock::create($data);

        $docJson = (string) $request->input('parsed_document_json', '');
        if ($docJson !== '') {
            $doc = json_decode($docJson, true);
            if (is_array($doc)) {
                DB::transaction(function () use ($doc, $stock) {
                    $issuer = $doc['issuer'] ?? [];
                    $auth = $doc['authorized_receiver'] ?? [];
                    $invoice = $doc['invoice'] ?? [];
                    $customer = $doc['customer'] ?? [];
                    $service = $doc['service'] ?? [];
                    $totals = $doc['totals'] ?? [];
                    $payment = $doc['payment'] ?? [];
                    $ppnRate = $totals['ppn_rate'] ?? null;
                    $ppnRateInt = is_string($ppnRate) ? (int) preg_replace('/[^0-9]/', '', $ppnRate) : (is_numeric($ppnRate) ? (int) $ppnRate : null);
                    $dateIso = $invoice['date_iso'] ?? null;
                    $date = $dateIso ? Carbon::parse($dateIso)->toDateString() : null;
                    $documentId = DB::table('gold_stock_documents')->insertGetId([
                        'master_gold_stock_id' => $stock->id,
                        'issuer_company' => $issuer['company'] ?? null,
                        'issuer_business_unit' => $issuer['business_unit'] ?? null,
                        'issuer_address' => $issuer['address'] ?? null,
                        'issuer_website' => $issuer['website'] ?? null,
                        'issuer_phone' => $issuer['phone'] ?? null,
                        'issuer_npwp' => $issuer['npwp'] ?? null,
                        'issuer_npwp_holder' => $issuer['npwp_holder'] ?? null,
                        'issuer_npwp_address' => $issuer['npwp_address'] ?? null,
                        'authorized_receiver_name' => $auth['name'] ?? null,
                        'authorized_receiver_nik' => $auth['nik'] ?? null,
                        'invoice_number' => $invoice['number'] ?? null,
                        'reference' => $invoice['reference'] ?? null,
                        'date_raw' => $invoice['date_raw'] ?? null,
                        'date' => $date,
                        'transaction_type' => $invoice['transaction_type'] ?? null,
                        'customer_name' => $customer['name'] ?? null,
                        'membership_number' => ($customer['membership']['number'] ?? null),
                        'membership_tier' => ($customer['membership']['tier'] ?? null),
                        'service_name' => $service['name'] ?? null,
                        'boutique_code_name' => ($service['boutique']['code_name'] ?? null),
                        'boutique_location' => ($service['boutique']['location'] ?? null),
                        'grand_total_idr' => (int) ($totals['grand_total_idr'] ?? 0),
                        'dpp_idr' => (int) ($totals['dpp_idr'] ?? 0),
                        'ppn_rate' => $ppnRateInt,
                        'ppn_idr' => (int) ($totals['ppn_idr'] ?? 0),
                        'currency' => (string) ($totals['currency'] ?? 'IDR'),
                        'payment_method' => $payment['method'] ?? null,
                        'virtual_account' => $payment['virtual_account'] ?? null,
                        'payment_no' => $payment['payment_no'] ?? null,
                        'created_by' => $payment['created_by'] ?? null,
                        'print_by' => $payment['print_by'] ?? null,
                        'raw_text' => $doc['raw_text'] ?? null,
                        'notes' => isset($doc['notes']) ? json_encode($doc['notes'], JSON_UNESCAPED_UNICODE) : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    foreach (($doc['items'] ?? []) as $it) {
                        DB::table('gold_stock_document_items')->insert([
                            'document_id' => $documentId,
                            'no' => (int) ($it['no'] ?? 0),
                            'description' => (string) ($it['description'] ?? ''),
                            'quantity_pcs' => (int) ($it['quantity_pcs'] ?? 0),
                            'weight_kg' => (float) ($it['weight_kg'] ?? 0),
                            'unit_price_idr' => (int) ($it['unit_price_idr'] ?? 0),
                            'total_idr' => (int) ($it['total_idr'] ?? 0),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                });
            }
        }

        return redirect()->route('admin.master.gold-stocks.index')->with('success', 'Stok emas berhasil ditambahkan.');
    }

    public function edit(MasterGoldStock $stock)
    {
        $mitras = MasterMitraBrankas::orderBy('nama_lengkap')->get(['id','nama_lengkap','kode_mitra']);
        return view('admin.master_gold_stock.edit', compact('stock', 'mitras'));
    }

    public function update(Request $request, MasterGoldStock $stock)
    {
        $data = $request->validate([
            'master_mitra_brankas_id' => ['nullable', 'integer', 'exists:master_mitra_brankas,id'],
            'gramasi'                 => ['required', 'numeric', 'min:0'],
            'qty'                     => ['required', 'integer', 'min:0'],
            'no_faktur'               => ['nullable', 'string', 'max:100'],
            'uraian'                  => ['nullable', 'string'],
            'berat'                   => ['required', 'numeric', 'min:0'],
            'harga'                   => ['required', 'numeric', 'min:0'],
            'total_pembayaran'        => ['required', 'numeric', 'min:0'],
            'uang_modal_mitra'        => ['nullable', 'numeric', 'min:0'],
            'uang_ganti_jajan_emas'   => ['nullable', 'numeric', 'min:0'],
            'uang_komisi_mitra'       => ['nullable', 'numeric', 'min:0'],
            'total_komisi'            => ['nullable', 'numeric', 'min:0'],
            'status_pengambilan'      => ['nullable', 'string', 'in:belum_diambil,sudah_diambil'],
            'file_faktur'             => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:51200'],
            'file_faktur_url'         => ['nullable', 'string', 'max:255'],
            'struk_komisi'            => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:51200'],
            'struk_komisi_url'        => ['nullable', 'string', 'max:255'],
            'struk_bayar_mitra'       => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:51200'],
            'struk_bayar_mitra_url'   => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('file_faktur')) {
            $dir = public_path('uploads/gold_stocks/faktur');
            File::ensureDirectoryExists($dir);
            $file = $request->file('file_faktur');
            $ext = strtolower($file->getClientOriginalExtension() ?: '');
            $filename = uniqid('faktur_', true) . ($ext ? '.' . $ext : '');
            $file->move($dir, $filename);
            $data['file_faktur_url'] = 'uploads/gold_stocks/faktur/' . $filename;
        }
        if ($request->hasFile('struk_komisi')) {
            $dir = public_path('uploads/gold_stocks/struk_komisi');
            File::ensureDirectoryExists($dir);
            $file = $request->file('struk_komisi');
            $ext = strtolower($file->getClientOriginalExtension() ?: '');
            $filename = uniqid('struk_komisi_', true) . ($ext ? '.' . $ext : '');
            $file->move($dir, $filename);
            $data['struk_komisi_url'] = 'uploads/gold_stocks/struk_komisi/' . $filename;
        }
        if ($request->hasFile('struk_bayar_mitra')) {
            $dir = public_path('uploads/gold_stocks/struk_bayar_mitra');
            File::ensureDirectoryExists($dir);
            $file = $request->file('struk_bayar_mitra');
            $ext = strtolower($file->getClientOriginalExtension() ?: '');
            $filename = uniqid('struk_bayar_mitra_', true) . ($ext ? '.' . $ext : '');
            $file->move($dir, $filename);
            $data['struk_bayar_mitra_url'] = 'uploads/gold_stocks/struk_bayar_mitra/' . $filename;
        }

        $stock->update($data);

        return redirect()->route('admin.master.gold-stocks.index')->with('success', 'Stok emas berhasil diupdate.');
    }

    public function destroy(MasterGoldStock $stock)
    {
        $stock->delete();

        return redirect()->route('admin.master.gold-stocks.index')->with('success', 'Stok emas berhasil dihapus.');
    }

    public function bulkUpdatePengambilanStatus(Request $request)
    {
        $data = $request->validate([
            'no_fakturs' => ['required','array','min:1'],
            'no_fakturs.*' => ['string','max:100'],
            'status_pengambilan' => ['required', Rule::in(['belum_diambil','sudah_diambil'])],
        ]);

        MasterGoldStock::whereIn('no_faktur', (array) $data['no_fakturs'])
            ->update(['status_pengambilan' => (string) $data['status_pengambilan']]);

        return response()->json(['success' => true]);
    }
}