<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\MasterCustomer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class CustomerAuthController extends Controller
{
    public function showLoginForm(): RedirectResponse|View
    {
        if (auth()->check() && auth()->user()->role === 'customer') {
            return redirect()->route('customer.dashboard');
        }
        return view('front.customer.login');
    }

    public function showRegisterForm(): RedirectResponse|View
    {
        if (auth()->check() && auth()->user()->role === 'customer') {
            return redirect()->route('customer.dashboard');
        }
        return view('front.customer.register');
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

            if (! $user || $user->role !== 'customer' || ! $user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akses khusus customer aktif.'])->withInput();
            }

            $user->forceFill(['last_login_at' => now()])->save();
            return redirect()->route('customer.dashboard');
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

        $data = $request->validate([
            'name' => ['string', 'max:100'],
            'email' => ['email', 'max:150', 'unique:sys_user,email'],
            'password' => ['min:6', 'confirmed'],
            'password_confirmation' => ['required'],
            'phone_wa' => ['nullable', 'string', 'max:30', 'unique:master_customer,phone_wa'],
            'address_line' => ['nullable', 'string', 'max:255'],
        ]);

        $user = null;

        DB::transaction(function () use ($data, &$user) {
            $user = new User([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'customer',
                'is_active' => true,
            ]);
            $user->save();

            MasterCustomer::create([
                'full_name' => $data['name'],
                'email' => $data['email'],
                'phone_wa' => $data['phone_wa'] ?? null,
                'address_line' => $data['address_line'] ?? null,
                'is_active' => true,
                'sys_user_id' => $user->id,
            ]);
        });

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('customer.login');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(['email' => $data['email']]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Instruksi reset password telah dikirim jika email terdaftar.');
        }

        return back()->withErrors(['email' => 'Gagal mengirim instruksi reset password.'])->withInput();
    }

    public function showResetForm(Request $request, string $token): View
    {
        $email = (string) $request->query('email', '');
        return view('front.customer.reset-pass', compact('token', 'email'));
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $status = Password::reset(
            $data,
            function (User $user) use ($data) {
                $user->forceFill([
                    'password' => Hash::make($data['password']),
                ])->save();
                $user->setRememberToken(Str::random(60));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('customer.login')->with('status', 'Password berhasil direset. Silakan login.');
        }

        return back()->withErrors(['email' => 'Token reset atau email tidak valid.'])->withInput();
    }
}