<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MasterMitraBrankas;
use Illuminate\Http\RedirectResponse;

class MitraAuthController extends Controller
{
    public function showLoginForm(): RedirectResponse|View
    {
        if (auth()->check() && auth()->user()->role === 'mitra') {
            return redirect()->route('mitra.dashboard');
        }
        return view('front.mitra.login');
    }

    public function showRegisterForm(): RedirectResponse|View
    {
        if (auth()->check() && auth()->user()->role === 'mitra') {
            return redirect()->route('mitra.dashboard');
        }
        return view('front.mitra.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if (! $user || $user->role !== 'mitra' || ! $user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akses khusus mitra aktif.'])->withInput();
            }
            $user->forceFill(['last_login_at' => now()])->save();
            return redirect()->route('mitra.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function register(Request $request)
    {
        $errors = [];
        foreach (['name', 'email', 'password'] as $field) {
            if (! $request->filled($field)) {
                $errors[$field] = 'Wajib diisi';
            }
        }
        if (! empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $rawPhone = $request->input('phone_wa');
        $normalizedPhone = $this->normalizePhoneWa(is_string($rawPhone) ? $rawPhone : null);
        if ($normalizedPhone !== null) {
            $request->merge(['phone_wa' => $normalizedPhone]);
        }

        $data = $request->validate([
            'name' => ['string', 'max:100'],
            'email' => ['email', 'max:150', 'unique:sys_user,email'],
            'password' => ['min:6', 'confirmed'],
            'password_confirmation' => ['required'],
            'phone_wa' => ['nullable', 'string', 'max:30', 'unique:master_mitra_brankas,phone_wa'],
        ]);

        $user = null;

        DB::transaction(function () use ($data, &$user) {
            $user = new User([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'mitra',
                'is_active' => true,
            ]);
            $user->save();

            $mitra = new MasterMitraBrankas([
                'nama_lengkap' => $data['name'],
                'email' => $data['email'],
                'phone_wa' => $data['phone_wa'] ?? null,
                'is_active' => false,
            ]);
            $mitra->sys_user_id = $user->id;
            $mitra->save();

            $user->master_mitra_brankas_id = $mitra->id;
            $user->save();
        });

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('mitra.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('mitra.login');
    }

    private function normalizePhoneWa(?string $phone): ?string
    {
        if ($phone === null || $phone === '') {
            return null;
        }
        $p = preg_replace('/[^0-9+]/', '', $phone);
        if (strpos($p, '+62') === 0) {
            $p = '0' . substr($p, 3);
        } elseif (strpos($p, '62') === 0) {
            $p = '0' . substr($p, 2);
        }
        return $p;
    }
}