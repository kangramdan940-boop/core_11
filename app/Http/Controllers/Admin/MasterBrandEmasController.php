<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterBrandEmas;
use Illuminate\Http\Request;

class MasterBrandEmasController extends Controller
{
    public function index()
    {
        $brands = MasterBrandEmas::orderBy('nama_brand')->get();
        return view('admin.master_brand_emas.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.master_brand_emas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_brand' => ['required', 'string', 'max:50', 'unique:master_brand_emas,kode_brand'],
            'nama_brand' => ['required', 'string', 'max:100', 'unique:master_brand_emas,nama_brand'],
            'deskripsi'  => ['nullable', 'string'],
            'image'      => ['nullable', 'image', 'max:3072'],
            'image_url'  => ['nullable', 'string', 'max:255'],
            'is_active'  => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $dir = public_path('uploads/brand_images');
            \Illuminate\Support\Facades\File::ensureDirectoryExists($dir);
            $file = $request->file('image');
            $filename = uniqid('brand_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $data['image_url'] = 'uploads/brand_images/' . $filename;
        }

        MasterBrandEmas::create($data);

        return redirect()
            ->route('admin.master.brand-emas.index')
            ->with('success', 'Brand emas berhasil ditambahkan.');
    }

    public function edit(MasterBrandEmas $brand)
    {
        return view('admin.master_brand_emas.edit', compact('brand'));
    }

    public function update(Request $request, MasterBrandEmas $brand)
    {
        $data = $request->validate([
            'kode_brand' => ['required', 'string', 'max:50', 'unique:master_brand_emas,kode_brand,' . $brand->id],
            'nama_brand' => ['required', 'string', 'max:100', 'unique:master_brand_emas,nama_brand,' . $brand->id],
            'deskripsi'  => ['nullable', 'string'],
            'image'      => ['nullable', 'image', 'max:3072'],
            'image_url'  => ['nullable', 'string', 'max:255'],
            'is_active'  => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $dir = public_path('uploads/brand_images');
            \Illuminate\Support\Facades\File::ensureDirectoryExists($dir);
            $file = $request->file('image');
            $filename = uniqid('brand_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $data['image_url'] = 'uploads/brand_images/' . $filename;
        }

        $brand->update($data);

        return redirect()
            ->route('admin.master.brand-emas.index')
            ->with('success', 'Brand emas berhasil diupdate.');
    }

    public function destroy(MasterBrandEmas $brand)
    {
        $brand->delete();

        return redirect()
            ->route('admin.master.brand-emas.index')
            ->with('success', 'Brand emas berhasil dihapus.');
    }
}