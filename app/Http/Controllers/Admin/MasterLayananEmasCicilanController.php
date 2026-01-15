<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterLayananEmasCicilan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterLayananEmasCicilanController extends Controller
{
    public function index()
    {
        $items = MasterLayananEmasCicilan::orderBy('nama_layanan')->get();
        return view('admin.master_layanan_emas_cicilan.index', compact('items'));
    }

    public function create()
    {
        return view('admin.master_layanan_emas_cicilan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_layanan'             => ['required', 'string', 'max:50', 'unique:master_layanan_emas_cicilan,kode_layanan'],
            'nama_layanan'             => ['required', 'string', 'max:150'],
            'tenor_min_bulan'          => ['required', 'integer', 'min:1', 'max:60'],
            'tenor_max_bulan'          => ['required', 'integer', 'min:1', 'max:60'],
            'dp_min_persen'            => ['required', 'numeric', 'min:0', 'max:100'],
            'dp_max_persen'            => ['required', 'numeric', 'min:0', 'max:100'],
            'margin_persen'            => ['nullable', 'numeric', 'min:0', 'max:100'],
            'margin_konfigurasi'       => ['nullable', 'array'],
            'biaya_admin'              => ['nullable', 'numeric', 'min:0'],
            'denda_terlambat_persen'   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'denda_terlambat_fixed'    => ['nullable', 'numeric', 'min:0'],
            'grace_period_hari'        => ['nullable', 'integer', 'min:0', 'max:31'],
            'allowed_delivery_types'   => ['nullable', 'array'],
            'allowed_delivery_types.*' => ['in:ship,pickup'],
            'is_active'                => ['sometimes', 'accepted'],
            'catatan'                  => ['nullable', 'string'],
        ]);

        $data['is_active'] = $request->has('is_active');

        MasterLayananEmasCicilan::create($data);

        return redirect()
            ->route('admin.master.layanan-emas-cicilan.index')
            ->with('success', 'Layanan emas cicilan berhasil ditambahkan.');
    }

    public function edit(MasterLayananEmasCicilan $item)
    {
        return view('admin.master_layanan_emas_cicilan.edit', compact('item'));
    }

    public function update(Request $request, MasterLayananEmasCicilan $item)
    {
        $data = $request->validate([
            'kode_layanan'             => ['required', 'string', 'max:50', Rule::unique('master_layanan_emas_cicilan', 'kode_layanan')->ignore($item->id)],
            'nama_layanan'             => ['required', 'string', 'max:150'],
            'tenor_min_bulan'          => ['required', 'integer', 'min:1', 'max:60'],
            'tenor_max_bulan'          => ['required', 'integer', 'min:1', 'max:60'],
            'dp_min_persen'            => ['required', 'numeric', 'min:0', 'max:100'],
            'dp_max_persen'            => ['required', 'numeric', 'min:0', 'max:100'],
            'margin_persen'            => ['nullable', 'numeric', 'min:0', 'max:100'],
            'margin_konfigurasi'       => ['nullable', 'array'],
            'biaya_admin'              => ['nullable', 'numeric', 'min:0'],
            'denda_terlambat_persen'   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'denda_terlambat_fixed'    => ['nullable', 'numeric', 'min:0'],
            'grace_period_hari'        => ['nullable', 'integer', 'min:0', 'max:31'],
            'allowed_delivery_types'   => ['nullable', 'array'],
            'allowed_delivery_types.*' => ['in:ship,pickup'],
            'is_active'                => ['sometimes', 'accepted'],
            'catatan'                  => ['nullable', 'string'],
        ]);

        $data['is_active'] = $request->has('is_active');

        $item->update($data);

        return redirect()
            ->route('admin.master.layanan-emas-cicilan.index')
            ->with('success', 'Layanan emas cicilan berhasil diupdate.');
    }

    public function destroy(MasterLayananEmasCicilan $item)
    {
        $item->delete();

        return redirect()
            ->route('admin.master.layanan-emas-cicilan.index')
            ->with('success', 'Layanan emas cicilan berhasil dihapus.');
    }
}