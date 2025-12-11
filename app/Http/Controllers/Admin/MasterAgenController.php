<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterAgen;
use App\Models\User;
use Illuminate\Http\Request;

class MasterAgenController extends Controller
{
    public function index()
    {
        $agens = MasterAgen::orderByDesc('id')->get();
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
            'rekening_nomor'=> ['nullable', 'string', 'max:50'],
            'is_active'    => ['sometimes', 'accepted'],
            'create_login' => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

        $agen = MasterAgen::create($data);

        if ($request->has('create_login')) {
            $request->validate([
                'password' => ['required','string','min:8','confirmed'],
            ]);

            $user = User::where('email', $agen->email)->first();

            if (! $user) {
                $user = User::create([
                    'name' => $agen->name,
                    'email' => $agen->email,
                    'password' => $request->input('password'),
                    'role' => 'agen',
                    'master_agen_id' => $agen->id,
                    'is_active' => true,
                ]);
            } else {
                $user->update([
                    'role' => 'agen',
                    'master_agen_id' => $agen->id,
                    'is_active' => true,
                ]);
                if ($request->filled('password')) {
                    $user->password = $request->input('password');
                    $user->save();
                }
            }

            $user->assignRole('agen');
        }

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
            'rekening_nomor'=> ['nullable', 'string', 'max:50'],
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