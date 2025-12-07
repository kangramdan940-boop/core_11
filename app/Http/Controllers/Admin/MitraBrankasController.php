<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterMitraBrankas;
use Illuminate\Http\Request;

class MitraBrankasController extends Controller
{
    public function index()
    {
        $mitras = MasterMitraBrankas::orderByDesc('id')->paginate(20);
        return view('admin.master_mitra_brankas.index', compact('mitras'));
    }

    public function create()
    {
        return view('admin.master_mitra_brankas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap'       => ['required', 'string', 'max:150'],
            'email'              => ['required', 'email', 'max:150', 'unique:master_mitra_brankas,email'],
            'phone_wa'           => ['required', 'string', 'max:30', 'unique:master_mitra_brankas,phone_wa'],
            'kode_mitra'         => ['required', 'string', 'max:50', 'unique:master_mitra_brankas,kode_mitra'],
            'platform'           => ['required', 'string', 'max:50'],
            'account_no'         => ['nullable', 'string', 'max:100'],
            'harian_limit_gram'  => ['required', 'numeric', 'min:0'],
            'komisi_persen'      => ['required', 'numeric', 'min:0', 'max:100'],
            'is_active'          => ['sometimes', 'accepted'],
            'is_edit'            => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['is_edit']   = $request->has('is_edit');

        MasterMitraBrankas::create($data);

        return redirect()
            ->route('admin.master.mitra-brankas.index')
            ->with('success', 'Mitra Brankas berhasil ditambahkan.');
    }

    public function edit(MasterMitraBrankas $mitra)
    {
        return view('admin.master_mitra_brankas.edit', compact('mitra'));
    }

    public function update(Request $request, MasterMitraBrankas $mitra)
    {
        $data = $request->validate([
            'nama_lengkap'       => ['required', 'string', 'max:150'],
            'email'              => ['required', 'email', 'max:150', 'unique:master_mitra_brankas,email,' . $mitra->id],
            'phone_wa'           => ['required', 'string', 'max:30', 'unique:master_mitra_brankas,phone_wa,' . $mitra->id],
            'kode_mitra'         => ['required', 'string', 'max:50', 'unique:master_mitra_brankas,kode_mitra,' . $mitra->id],
            'platform'           => ['required', 'string', 'max:50'],
            'account_no'         => ['nullable', 'string', 'max:100'],
            'harian_limit_gram'  => ['required', 'numeric', 'min:0'],
            'komisi_persen'      => ['required', 'numeric', 'min:0', 'max:100'],
            'is_active'          => ['sometimes', 'accepted'],
            'is_edit'            => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['is_edit']   = $request->has('is_edit');
        $mitra->update($data);

        return redirect()
            ->route('admin.master.mitra-brankas.index')
            ->with('success', 'Mitra Brankas berhasil diupdate.');
    }

    public function destroy(MasterMitraBrankas $mitra)
    {
        $mitra->delete();

        return redirect()
            ->route('admin.master.mitra-brankas.index')
            ->with('success', 'Mitra Brankas berhasil dihapus.');
    }
}