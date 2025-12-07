<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterProdukDanLayanan;
use App\Models\MasterGramasiEmas;
use Illuminate\Http\Request;

class MasterProdukDanLayananController extends Controller
{
    public function index()
    {
        $items = MasterProdukDanLayanan::with('gramasi')->orderByDesc('id')->paginate(20);
        return view('admin.master_produk_dan_layanan.index', compact('items'));
    }

    public function create()
    {
        $gramasis = MasterGramasiEmas::orderBy('gramasi')->get();
        return view('admin.master_produk_dan_layanan.create', compact('gramasis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_gramasi'     => ['required', 'integer', 'exists:master_gramasi_emas,id'],
            'harga_hariini'  => ['required', 'numeric', 'min:0'],
            'is_allow_ready' => ['sometimes', 'accepted'],
            'is_allow_po'    => ['sometimes', 'accepted'],
            'harga_jasa'     => ['nullable', 'numeric', 'min:0'],
            'image'          => ['nullable', 'image', 'max:3072'],
            'image_produk'   => ['nullable', 'string', 'max:255'],
            'expired_dae'    => ['nullable', 'date'],
            'urutan'         => ['nullable', 'integer', 'min:0'],
            'status'         => ['required', 'string', 'max:30'],
        ]);

        $data['is_allow_ready'] = $request->has('is_allow_ready');
        $data['is_allow_po']    = $request->has('is_allow_po');

        if ($request->hasFile('image')) {
            $dir = public_path('uploads/produk_images');
            \Illuminate\Support\Facades\File::ensureDirectoryExists($dir);
            $file = $request->file('image');
            $filename = uniqid('produk_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $data['image_produk'] = 'uploads/produk_images/' . $filename;
        }

        MasterProdukDanLayanan::create($data);

        return redirect()
            ->route('admin.master.produk-layanan.index')
            ->with('success', 'Produk/Layanan berhasil ditambahkan.');
    }

    public function edit(MasterProdukDanLayanan $item)
    {
        $gramasis = MasterGramasiEmas::orderBy('gramasi')->get();
        return view('admin.master_produk_dan_layanan.edit', compact('item', 'gramasis'));
    }

    public function update(Request $request, MasterProdukDanLayanan $item)
    {
        $data = $request->validate([
            'id_gramasi'     => ['required', 'integer', 'exists:master_gramasi_emas,id'],
            'harga_hariini'  => ['required', 'numeric', 'min:0'],
            'is_allow_ready' => ['sometimes', 'accepted'],
            'is_allow_po'    => ['sometimes', 'accepted'],
            'harga_jasa'     => ['nullable', 'numeric', 'min:0'],
            'image'          => ['nullable', 'image', 'max:3072'],
            'image_produk'   => ['nullable', 'string', 'max:255'],
            'expired_dae'    => ['nullable', 'date'],
            'urutan'         => ['nullable', 'integer', 'min:0'],
            'status'         => ['required', 'string', 'max:30'],
        ]);

        $data['is_allow_ready'] = $request->has('is_allow_ready');
        $data['is_allow_po']    = $request->has('is_allow_po');

        if ($request->hasFile('image')) {
            $dir = public_path('uploads/produk_images');
            \Illuminate\Support\Facades\File::ensureDirectoryExists($dir);
            $file = $request->file('image');
            $filename = uniqid('produk_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $data['image_produk'] = 'uploads/produk_images/' . $filename;
        }

        $item->update($data);

        return redirect()
            ->route('admin.master.produk-layanan.index')
            ->with('success', 'Produk/Layanan berhasil diupdate.');
    }

    public function destroy(MasterProdukDanLayanan $item)
    {
        $item->delete();

        return redirect()
            ->route('admin.master.produk-layanan.index')
            ->with('success', 'Produk/Layanan berhasil dihapus.');
    }
}