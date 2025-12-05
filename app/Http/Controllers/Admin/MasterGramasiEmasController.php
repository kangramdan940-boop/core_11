<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterGramasiEmas;
use App\Models\MasterBrandEmas;
use Illuminate\Http\Request;

class MasterGramasiEmasController extends Controller
{
    public function index()
    {
        $gramasis = MasterGramasiEmas::orderBy('gramasi')->paginate(20);
        return view('admin.master_gramasi_emas.index', compact('gramasis'));
    }

    public function create()
    {
        $brands = MasterBrandEmas::orderBy('nama_brand')->get();
        return view('admin.master_gramasi_emas.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'gramasi'  => ['required', 'numeric', 'min:0.001'],
            'catatan'  => ['nullable', 'string'],
            'is_active'=> ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

        MasterGramasiEmas::create($data);

        return redirect()
            ->route('admin.master.gramasi-emas.index')
            ->with('success', 'Gramasi emas berhasil ditambahkan.');
    }

    public function edit(MasterGramasiEmas $item)
    {
        $brands = MasterBrandEmas::orderBy('nama_brand')->get();
        return view('admin.master_gramasi_emas.edit', compact('item', 'brands'));
    }

    public function update(Request $request, MasterGramasiEmas $item)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'gramasi'  => ['required', 'numeric', 'min:0.001'],
            'catatan'  => ['nullable', 'string'],
            'is_active'=> ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

        $item->update($data);

        return redirect()
            ->route('admin.master.gramasi-emas.index')
            ->with('success', 'Gramasi emas berhasil diupdate.');
    }

    public function destroy(MasterGramasiEmas $item)
    {
        $item->delete();

        return redirect()
            ->route('admin.master.gramasi-emas.index')
            ->with('success', 'Gramasi emas berhasil dihapus.');
    }
}