<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterAdmin;
use Illuminate\Http\Request;

class MasterAdminController extends Controller
{
    public function index()
    {
        $admins = MasterAdmin::orderByDesc('id')->paginate(20);
        return view('admin.master_admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.master_admin.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'email'          => ['required', 'email', 'max:150', 'unique:master_admin,email'],
            'phone_wa'       => ['required', 'string', 'max:30', 'unique:master_admin,phone_wa'],
            'jabatan'        => ['nullable', 'string', 'max:100'],
            'is_super_admin' => ['sometimes', 'accepted'],
            'is_active'      => ['sometimes', 'accepted'],
        ]);

        $data['is_super_admin'] = $request->has('is_super_admin');
        $data['is_active'] = $request->has('is_active');

        MasterAdmin::create($data);

        return redirect()
            ->route('admin.master.admins.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(MasterAdmin $admin)
    {
        return view('admin.master_admin.edit', compact('admin'));
    }

    public function update(Request $request, MasterAdmin $admin)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'email'          => ['required', 'email', 'max:150', 'unique:master_admin,email,' . $admin->id],
            'phone_wa'       => ['required', 'string', 'max:30', 'unique:master_admin,phone_wa,' . $admin->id],
            'jabatan'        => ['nullable', 'string', 'max:100'],
            'is_super_admin' => ['sometimes', 'accepted'],
            'is_active'      => ['sometimes', 'accepted'],
        ]);

        $data['is_super_admin'] = $request->has('is_super_admin');
        $data['is_active'] = $request->has('is_active');

        $admin->update($data);

        return redirect()
            ->route('admin.master.admins.index')
            ->with('success', 'Admin berhasil diupdate.');
    }

    public function destroy(MasterAdmin $admin)
    {
        $admin->delete();

        return redirect()
            ->route('admin.master.admins.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}