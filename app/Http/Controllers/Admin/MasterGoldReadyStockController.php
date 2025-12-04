<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterGoldReadyStock;
use App\Models\MasterAgen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterGoldReadyStockController extends Controller
{
    public function index()
    {
        $stocks = MasterGoldReadyStock::with('agen')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.master_gold_ready_stock.index', compact('stocks'));
    }

    public function create()
    {
        $agens = MasterAgen::orderBy('name')->get(['id', 'name', 'kode_agen']);
        return view('admin.master_gold_ready_stock.create', compact('agens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'master_agen_id'     => ['nullable', 'integer', 'exists:master_agen,id'],
            'kode_item'          => ['required', 'string', 'max:100', 'unique:master_gold_ready_stock,kode_item'],
            'brand'              => ['required', 'string', 'max:50'],
            'gramasi'            => ['required', 'numeric', 'min:0.001'],
            'nomor_seri'         => ['nullable', 'string', 'max:100'],
            'tahun_cetak'        => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'kondisi_barang'     => ['required', Rule::in(['mint','second'])],
            'status'             => ['required', Rule::in(['available','reserved','sold'])],
            'harga_beli'         => ['nullable', 'numeric', 'min:0'],
            'harga_jual_minimal' => ['nullable', 'numeric', 'min:0'],
            'harga_jual_fix'     => ['nullable', 'numeric', 'min:0'],
            'lokasi_simpan'      => ['nullable', 'string', 'max:150'],
            'catatan'            => ['nullable', 'string'],
            'is_active'          => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

        MasterGoldReadyStock::create($data);

        return redirect()
            ->route('admin.master.ready-stocks.index')
            ->with('success', 'Stok emas berhasil ditambahkan.');
    }

    public function edit(MasterGoldReadyStock $stock)
    {
        $agens = MasterAgen::orderBy('name')->get(['id', 'name', 'kode_agen']);
        return view('admin.master_gold_ready_stock.edit', compact('stock', 'agens'));
    }

    public function update(Request $request, MasterGoldReadyStock $stock)
    {
        $data = $request->validate([
            'master_agen_id'     => ['nullable', 'integer', 'exists:master_agen,id'],
            'kode_item'          => ['required', 'string', 'max:100', Rule::unique('master_gold_ready_stock', 'kode_item')->ignore($stock->id)],
            'brand'              => ['required', 'string', 'max:50'],
            'gramasi'            => ['required', 'numeric', 'min:0.001'],
            'nomor_seri'         => ['nullable', 'string', 'max:100'],
            'tahun_cetak'        => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'kondisi_barang'     => ['required', Rule::in(['mint','second'])],
            'status'             => ['required', Rule::in(['available','reserved','sold'])],
            'harga_beli'         => ['nullable', 'numeric', 'min:0'],
            'harga_jual_minimal' => ['nullable', 'numeric', 'min:0'],
            'harga_jual_fix'     => ['nullable', 'numeric', 'min:0'],
            'lokasi_simpan'      => ['nullable', 'string', 'max:150'],
            'catatan'            => ['nullable', 'string'],
            'is_active'          => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

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