<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterGoldReadyStock;
use App\Models\MasterAgen;
use App\Models\MasterAsset;
use App\Models\MasterGramasiEmas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterGoldReadyStockController extends Controller
{
    public function index()
    {
        $stocks = MasterGoldReadyStock::with('agen')
            ->orderByDesc('id')
            ->get();

        return view('admin.master_gold_ready_stock.index', compact('stocks'));
    }

    public function create()
    {
        $agens = MasterAgen::orderBy('name')->get(['id', 'name', 'kode_agen']);
        $assets = MasterAsset::where('status', 'active')->orderBy('title')->get(['id','title','url','file_extension']);
        $gramasis = MasterGramasiEmas::where('is_active', true)->orderBy('gramasi')->get(['id','nama','gramasi']);
        return view('admin.master_gold_ready_stock.create', compact('agens', 'assets', 'gramasis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'master_agen_id'       => ['nullable', 'integer', 'exists:master_agen,id'],
            'kode_item'            => ['required', 'string', 'max:100', 'unique:master_gold_ready_stock,kode_item'],
            'brand'                => ['required', 'string', 'max:50'],
            'id_gramasi'           => ['required', 'integer', 'exists:master_gramasi_emas,id'],
            'nomor_seri'           => ['nullable', 'string', 'max:100'],
            'tahun_cetak'          => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'kondisi_barang'       => ['required', Rule::in(['mint','second'])],
            'status'               => ['required', Rule::in(['available','reserved','sold'])],
            'harga_beli'           => ['nullable', 'numeric', 'min:0'],
            'harga_jual_minimal'   => ['nullable', 'numeric', 'min:0'],
            'harga_jual_fix'       => ['nullable', 'numeric', 'min:0'],
            'lokasi_simpan'        => ['nullable', 'string', 'max:150'],
            'catatan'              => ['nullable', 'string'],
            'is_active'            => ['sometimes', 'accepted'],
            'nama_produk'          => ['nullable', 'string', 'max:255'],
            'images'               => ['nullable', 'string'],
            'video_url'            => ['nullable', 'string'],
            'deskripsi_pengiriman' => ['nullable', 'string', 'max:255'],
            'jumlah_terjual'       => ['nullable', 'integer', 'min:0'],
            'acara'                => ['nullable', 'string', 'max:100'],
            'negara_asal'          => ['nullable', 'string', 'max:100'],
            'is_custom'            => ['sometimes', 'accepted'],
            'is_mystery_box'       => ['sometimes', 'accepted'],
            'tags'                 => ['nullable', 'string'],
        ]);

        if (isset($data['id_gramasi'])) {
            $g = MasterGramasiEmas::find((int) $data['id_gramasi']);
            $data['gramasi'] = $g ? (float) $g->gramasi : null;
            unset($data['id_gramasi']);
        }

        if (isset($data['id_gramasi'])) {
            $g = MasterGramasiEmas::find((int) $data['id_gramasi']);
            $data['gramasi'] = $g ? (float) $g->gramasi : null;
            unset($data['id_gramasi']);
        }

        $data['is_active'] = $request->has('is_active');
        $data['is_custom'] = $request->has('is_custom');
        $data['is_mystery_box'] = $request->has('is_mystery_box');

        $imagesText = (string) ($request->input('images') ?? '');
        if ($imagesText !== '') {
            $urls = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', $imagesText))));
            $data['images'] = $urls;
        }

        MasterGoldReadyStock::create($data);

        return redirect()
            ->route('admin.master.ready-stocks.index')
            ->with('success', 'Stok emas berhasil ditambahkan.');
    }

    public function edit(MasterGoldReadyStock $stock)
    {
        $agens = MasterAgen::orderBy('name')->get(['id', 'name', 'kode_agen']);
        $assets = MasterAsset::where('status', 'active')->orderBy('title')->get(['id','title','url','file_extension']);
        $gramasis = MasterGramasiEmas::where('is_active', true)->orderBy('gramasi')->get(['id','nama','gramasi']);
        $selectedGramasiId = MasterGramasiEmas::where('gramasi', $stock->gramasi)->value('id');
        return view('admin.master_gold_ready_stock.edit', compact('stock', 'agens', 'assets', 'gramasis', 'selectedGramasiId'));
    }

    public function update(Request $request, MasterGoldReadyStock $stock)
    {
        $data = $request->validate([
            'master_agen_id'       => ['nullable', 'integer', 'exists:master_agen,id'],
            'kode_item'            => ['required', 'string', 'max:100', Rule::unique('master_gold_ready_stock', 'kode_item')->ignore($stock->id)],
            'brand'                => ['required', 'string', 'max:50'],
            'id_gramasi'           => ['required', 'integer', 'exists:master_gramasi_emas,id'],
            'nomor_seri'           => ['nullable', 'string', 'max:100'],
            'tahun_cetak'          => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'kondisi_barang'       => ['required', Rule::in(['mint','second'])],
            'status'               => ['required', Rule::in(['available','reserved','sold'])],
            'harga_beli'           => ['nullable', 'numeric', 'min:0'],
            'harga_jual_minimal'   => ['nullable', 'numeric', 'min:0'],
            'harga_jual_fix'       => ['nullable', 'numeric', 'min:0'],
            'lokasi_simpan'        => ['nullable', 'string', 'max:150'],
            'catatan'              => ['nullable', 'string'],
            'is_active'            => ['sometimes', 'accepted'],
            'nama_produk'          => ['nullable', 'string', 'max:255'],
            'images'               => ['nullable', 'string'],
            'video_url'            => ['nullable', 'string'],
            'deskripsi_pengiriman' => ['nullable', 'string', 'max:255'],
            'jumlah_terjual'       => ['nullable', 'integer', 'min:0'],
            'acara'                => ['nullable', 'string', 'max:100'],
            'negara_asal'          => ['nullable', 'string', 'max:100'],
            'is_custom'            => ['sometimes', 'accepted'],
            'is_mystery_box'       => ['sometimes', 'accepted'],
            'tags'                 => ['nullable', 'string'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['is_custom'] = $request->has('is_custom');
        $data['is_mystery_box'] = $request->has('is_mystery_box');

        $imagesText = (string) ($request->input('images') ?? '');
        if ($imagesText !== '') {
            $urls = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', $imagesText))));
            $data['images'] = $urls;
        } else {
            $data['images'] = null;
        }

        $stock->update($data);

        return redirect()
            ->route('admin.master.ready-stocks.index')
            ->with('success', 'Stok emas berhasil diupdate.');
    }

    public function destroy(MasterGoldReadyStock $stock)
    {
        $stock->delete();

        return redirect()
            ->route('admin.master.ready-stocks.index')
            ->with('success', 'Stok emas berhasil dihapus.');
    }
}