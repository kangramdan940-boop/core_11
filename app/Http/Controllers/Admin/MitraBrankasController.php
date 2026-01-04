<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterMitraBrankas;
use Illuminate\Http\Request;

class MitraBrankasController extends Controller
{
    public function index()
    {
        $mitras = MasterMitraBrankas::orderByDesc('id')->get();
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

    public function setPasswordForm(MasterMitraBrankas $mitra)
    {
        $user = $mitra->user;
        return view('admin.master_mitra_brankas.set_password', compact('mitra', 'user'));
    }

    public function setPasswordUpdate(Request $request, MasterMitraBrankas $mitra)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'same:password'],
        ]);

        $user = $mitra->user;
        if (!$user) {
            if (!empty($mitra->email)) {
                $user = \App\Models\User::create([
                    'name' => $mitra->nama_lengkap ?? ('Mitra ' . $mitra->id),
                    'email' => $mitra->email,
                    'password' => $data['password'],
                    'role' => 'mitra',
                    'is_active' => true,
                ]);
                $mitra->sys_user_id = $user->id;
                $mitra->save();
            } else {
                return back()->withErrors(['email' => 'Email mitra belum diisi, tidak dapat membuat akun sys_user.']);
            }
        } else {
            $user->password = $data['password'];
            $user->save();
        }

        return redirect()->route('admin.master.mitra-brankas.index')->with('success', 'Password sys_user mitra diperbarui.');
    }
}