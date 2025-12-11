<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

final class RoleController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()?->hasRole('super_admin')) {
            abort(403);
        }

        $roles = Role::query()
            ->orderBy('name')
            ->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create(Request $request)
    {
        if (! $request->user()?->hasRole('super_admin')) {
            abort(403);
        }

        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        if (! $request->user()?->hasRole('super_admin')) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:roles,name,NULL,id,guard_name,web'],
        ]);

        Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Request $request, Role $role)
    {
        if (! $request->user()?->hasRole('super_admin')) {
            abort(403);
        }

        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        if (! $request->user()?->hasRole('super_admin')) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:roles,name,' . $role->id . ',id,guard_name,web'],
        ]);

        $role->update(['name' => $data['name']]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role berhasil diupdate.');
    }

    public function destroy(Request $request, Role $role)
    {
        if (! $request->user()?->hasRole('super_admin')) {
            abort(403);
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}