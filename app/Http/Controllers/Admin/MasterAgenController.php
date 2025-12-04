<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterAgen;
use Illuminate\Http\Request;

class MasterAgenController extends Controller
{
    public function index()
    {
        $agens = MasterAgen::orderByDesc('id')->paginate(20);
        return view('admin.master_agen.index', compact('agens'));
    }

    public function create()
    {
        return view('admin.master_agen.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'email'        => ['required', 'email', 'max:150', 'unique:master_agen,email'],
            'phone_wa'     => ['required', 'string', 'max:30', 'unique:master_agen,phone_wa'],
            'kode_agen'    => ['required', 'string', 'max:50', 'unique:master_agen,kode_agen'],
            'area'         => ['nullable', 'string', 'max:100'],
            'address_line' => ['nullable', 'string', 'max:255'],
            'is_active'    => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

        MasterAgen::create($data);

        return redirect()
            ->route('admin.master.agens.index')
            ->with('success', 'Agen berhasil ditambahkan.');
    }

    public function edit(MasterAgen $agen)
    {
        return view('admin.master_agen.edit', compact('agen'));
    }

    public function update(Request $request, MasterAgen $agen)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'email'        => ['required', 'email', 'max:150', 'unique:master_agen,email,' . $agen->id],
            'phone_wa'     => ['required', 'string', 'max:30', 'unique:master_agen,phone_wa,' . $agen->id],
            'kode_agen'    => ['required', 'string', 'max:50', 'unique:master_agen,kode_agen,' . $agen->id],
            'area'         => ['nullable', 'string', 'max:100'],
            'address_line' => ['nullable', 'string', 'max:255'],
            'is_active'    => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $agen->update($data);

        return redirect()
            ->route('admin.master.agens.index')
            ->with('success', 'Agen berhasil diupdate.');
    }

    public function destroy(MasterAgen $agen)
    {
        $agen->delete();

        return redirect()
            ->route('admin.master.agens.index')
            ->with('success', 'Agen berhasil dihapus.');
    }
}