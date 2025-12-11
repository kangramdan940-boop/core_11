<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

final class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()?->hasRole('super_admin')) {
            abort(403);
        }

        $users = User::query()
            ->with(['roles', 'permissions'])
            ->orderByDesc('id')
            ->get();

        return view('admin.permissions.index', compact('users'));
    }

    public function edit(Request $request, User $user)
    {
        if (! $request->user()?->hasRole('super_admin')) {
            abort(403);
        }

        $roles = Role::query()->orderBy('name')->get();
        $permissions = Permission::query()->orderBy('name')->get();

        return view('admin.permissions.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        if (! $request->user()?->hasRole('super_admin')) {
            abort(403);
        }

        $data = $request->validate([
            'roles' => ['array'],
            'roles.*' => ['string'],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        $roles = $data['roles'] ?? [];
        $permissions = $data['permissions'] ?? [];

        $user->syncRoles($roles);
        $user->syncPermissions($permissions);

        return redirect()
            ->route('admin.permissions.users.edit', $user)
            ->with('success', 'Hak akses pengguna berhasil diperbarui.');
    }
}