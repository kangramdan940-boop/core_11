<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class SysUserManagementController extends Controller
{
    private function ensureSuperAdmin(Request $request): void
    {
        if (! $request->user()?->hasRole('super_admin')) {
            abort(403);
        }
    }

    public function index(Request $request): View
    {
        $this->ensureSuperAdmin($request);
        $users = User::query()
            ->orderByDesc('id')
            ->get(['id','name','email','role','is_active','last_login_at']);
        return view('admin.login_management.index', compact('users'));
    }

    public function create(Request $request): View
    {
        $this->ensureSuperAdmin($request);
        $roles = ['super_admin','admin','agen','mitra','customer'];
        return view('admin.login_management.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin($request);

        $data = $request->validate([
            'name'                  => ['required','string','max:150'],
            'email'                 => ['required','email','max:150','unique:sys_user,email'],
            'role'                  => ['required','string', Rule::in(['super_admin','admin','agen','mitra','customer'])],
            'is_active'             => ['sometimes','accepted'],
            'password'              => ['required','string','min:8','confirmed'],
        ]);

        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => $data['password'],
            'role'       => $data['role'],
            'is_active'  => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.management-login.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function edit(Request $request, User $user): View
    {
        $this->ensureSuperAdmin($request);
        $roles = ['super_admin','admin','agen','mitra','customer'];
        return view('admin.login_management.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureSuperAdmin($request);

        $data = $request->validate([
            'name'                  => ['required','string','max:150'],
            'email'                 => ['required','email','max:150', Rule::unique('sys_user','email')->ignore($user->id)],
            'role'                  => ['required','string', Rule::in(['super_admin','admin','agen','mitra','customer'])],
            'is_active'             => ['sometimes','accepted'],
            'password'              => ['nullable','string','min:8','confirmed'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->is_active = $request->has('is_active');

        if ($request->filled('password')) {
            $user->password = (string) $data['password'];
        }

        $user->save();

        return redirect()
            ->route('admin.management-login.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->ensureSuperAdmin($request);

        if ($user->id === $request->user()->id) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus akun Anda sendiri.']);
        }

        $user->delete();

        return redirect()
            ->route('admin.management-login.index')
            ->with('success', 'User berhasil dihapus.');
    }
}