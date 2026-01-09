<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterAdmin;
use Illuminate\Http\Request;

class MasterAdminController extends Controller
{
    public function index()
    {
        $admins = MasterAdmin::orderByDesc('id')->get();
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

    public function setPasswordForm(MasterAdmin $admin)
    {
        $user = \App\Models\User::where('master_admin_id', $admin->id)->first();
        return view('admin.master_admin.set_password', compact('admin', 'user'));
    }

    public function setPasswordUpdate(Request $request, MasterAdmin $admin)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'same:password'],
        ]);

        $user = \App\Models\User::where('master_admin_id', $admin->id)->first();

        if (! $user) {
            if (! empty($admin->email)) {
                $user = \App\Models\User::create([
                    'name' => $admin->name ?? ('Admin ' . $admin->id),
                    'email' => $admin->email,
                    'password' => $data['password'],
                    'role' => $admin->is_super_admin ? 'super_admin' : 'admin',
                    'is_active' => $admin->is_active,
                ]);
                $user->master_admin_id = $admin->id;
                $user->save();
            } else {
                return back()->withErrors(['email' => 'Email admin belum diisi, tidak dapat membuat akun sys_user.']);
            }
        } else {
            $user->password = $data['password'];
            $user->role = $admin->is_super_admin ? 'super_admin' : 'admin';
            $user->is_active = $admin->is_active;
            $user->save();
        }

        return redirect()->route('admin.master.admins.index')->with('success', 'Password sys_user admin diperbarui.');
    }
}