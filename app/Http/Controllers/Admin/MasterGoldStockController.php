<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterGoldStock;
use App\Models\MasterMitraBrankas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

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

        MasterGoldStock::create($data);

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