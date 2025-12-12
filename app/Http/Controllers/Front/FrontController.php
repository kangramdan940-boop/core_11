<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\MasterHomeSlider;
use App\Models\MasterMenuHomeCustomer;
use App\Models\MasterProdukDanLayanan;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FrontController extends Controller
{
    public function home()
    {
        $sliders = MasterHomeSlider::orderBy('id')->get();

        $slides = $sliders->map(function (MasterHomeSlider $s) {
            $src = $this->resolveImageUrl($s->image_url);
            return [
                'image_src'   => $src,
                'title'       => $s->title ?? '',
                'description' => $s->description ?? '',
            ];
        });

        return view('front.home', ['slides' => $slides]);
    }

    private function resolveImageUrl(?string $imageUrl): string
    {
        if (!$imageUrl) {
            return asset('front/images/banner/boarding1.png');
        }
        if (Str::startsWith($imageUrl, ['http://', 'https://', '/'])) {
            return $imageUrl;
        }
        return asset(ltrim($imageUrl, '/'));
    }

    public function customerDashboard(): View
    {
        $customer = \App\Models\MasterCustomer::where('sys_user_id', auth()->id())->first();
        $orders = $customer
            ? \App\Models\TransPo::where('master_customer_id', $customer->id)->orderByDesc('id')->limit(10)->get()
            : collect();
        $readyOrders = $customer
            ? \App\Models\TransReady::where('master_customer_id', $customer->id)->orderByDesc('id')->limit(10)->get()
            : collect();
        $contracts = $customer
            ? \App\Models\TransCicilan::where('master_customer_id', $customer->id)->orderByDesc('id')->limit(10)->get()
            : collect();
        $menus = MasterMenuHomeCustomer::orderBy('id')->get();
        $produk = MasterProdukDanLayanan::orderBy('urutan')->limit(5)->get();

        $poGramTotal = $customer
            ? (float) \App\Models\TransPo::where('master_customer_id', $customer->id)
                ->where('status', '!=', 'cancelled')
                ->sum(DB::raw('COALESCE(total_gram,0) * COALESCE(qty,1)'))
            : 0.0;
        $readyGramTotal = 0.0;
        if ($customer) {
            $readyItems = \App\Models\TransReady::with('readyStock')
                ->where('master_customer_id', $customer->id)
                ->where('status', '!=', 'cancelled')
                ->get();
            $readyGramTotal = (float) $readyItems->sum(function($r){
                $g = (float) optional($r->readyStock)->gramasi;
                $q = (int) ($r->qty ?? 0);
                return $g * $q;
            });
        }

        $cicilanGramTotal = $customer
            ? (float) \App\Models\TransCicilan::where('master_customer_id', $customer->id)
                ->where('status', '!=', 'cancelled')
                ->sum('gramasi')
            : 0.0;

        return view('front.customer.dashboard', compact('customer', 'orders', 'readyOrders', 'contracts', 'menus', 'produk', 'poGramTotal', 'readyGramTotal', 'cicilanGramTotal'));
    }

    public function customerAllOrders(): View
    {
        $customer = \App\Models\MasterCustomer::where('sys_user_id', auth()->id())->first();
        $orders = $customer
            ? \App\Models\TransPo::where('master_customer_id', $customer->id)->orderByDesc('id')->get()
            : collect();
        $readyOrders = $customer
            ? \App\Models\TransReady::where('master_customer_id', $customer->id)->orderByDesc('id')->get()
            : collect();
        $contracts = $customer
            ? \App\Models\TransCicilan::where('master_customer_id', $customer->id)->orderByDesc('id')->get()
            : collect();

        return view('front.customer.all-order', compact('customer', 'orders', 'readyOrders', 'contracts'));
    }

    public function customerProductDanLayanan(): View
    {
        $customer = \App\Models\MasterCustomer::where('sys_user_id', auth()->id())->first();
        $produk = MasterProdukDanLayanan::orderBy('urutan')->get();
        return view('front.customer.product-dan-layanan', compact('customer', 'produk'));
    }

    public function customerProfile(): View
    {
        $customer = \App\Models\MasterCustomer::where('sys_user_id', auth()->id())->first();
        $menus = MasterMenuHomeCustomer::orderBy('id')->get();
        $produk = MasterProdukDanLayanan::orderBy('urutan')->limit(5)->get();

        $orderStats = [
            'po' => $customer ? \App\Models\TransPo::where('master_customer_id', $customer->id)->count() : 0,
            'ready' => $customer ? \App\Models\TransReady::where('master_customer_id', $customer->id)->count() : 0,
            'cicilan' => $customer ? \App\Models\TransCicilan::where('master_customer_id', $customer->id)->count() : 0,
        ];

        return view('front.customer.profile', compact('customer', 'menus', 'produk', 'orderStats'));
    }

    public function updateCustomerProfile(Request $request): RedirectResponse
    {
        $customer = \App\Models\MasterCustomer::where('sys_user_id', auth()->id())->firstOrFail();

        $data = $request->validate([
            'birth_date'   => ['nullable', 'date'],
            'address_line' => ['nullable', 'string', 'max:255'],
            'kota'         => ['nullable', 'string', 'max:100'],
            'provinsi'     => ['nullable', 'string', 'max:100'],
            'kode_pos'     => ['nullable', 'string', 'max:10'],
        ]);

        $customer->fill($data);
        $customer->save();

        return redirect()->route('customer.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function customerPoCreate(): View
    {
        $customer = \App\Models\MasterCustomer::where('sys_user_id', auth()->id())->first();
        $produkPo = MasterProdukDanLayanan::with('gramasi')->where('is_allow_po', true)->where('status', 'active')->orderBy('urutan')->get();
        return view('front.customer.po.create', compact('customer', 'produkPo'));
    }

    public function customerForgotPassword(): View
    {
        return view('front.customer.forget-pass');
    }

    public function customerSendForgotPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ], [
            'email.required' => 'Wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Maksimal 150 karakter',
        ]);

        $user = User::where('email', $data['email'])->where('role', 'customer')->first();

        if ($user && $user->is_active) {
            $payload = [
                'email' => $user->email,
                'exp' => now()->addMinutes(60)->toISOString(),
                'type' => 'customer',
                'nonce' => (string) Str::uuid(),
            ];
            $token = Crypt::encryptString(json_encode($payload));
            $resetUrl = route('customer.reset-password', ['token' => $token]);

            try {
                Mail::send('emails.customer.reset-password', [
                    'name' => $user->name ?? 'Customer',
                    'resetUrl' => $resetUrl,
                    'brandColor' => '#d9b846',
                ], function ($message) use ($user) {
                    $message->to($user->email)->subject('Reset Password Akun Anda');
                });
            } catch (\Throwable $e) {
                // Tetap balas pesan umum tanpa membocorkan status pengiriman
            }
        }

        return back()->with('message', 'Terima kasih. Jika email terdaftar, instruksi reset password telah dikirim.');
    }

    public function showResetPasswordForm(Request $request): View|RedirectResponse
    {
        $token = trim((string) $request->query('token', ''));
        if ($token === '') {
            return redirect()->route('customer.forgot-password')->withErrors(['email' => 'Token tidak valid.']);
        }
        $decoded = null;
        try {
            $decrypted = Crypt::decryptString($token);
            $decoded = json_decode($decrypted, true);
        } catch (\Throwable $e) {
            Log::warning('Reset password decrypt failed', ['error' => $e->getMessage()]);
            return redirect()->route('customer.forgot-password')->with('message', 'Token tidak valid atau telah kadaluarsa.');
        }
        if (!is_array($decoded) || ($decoded['type'] ?? '') !== 'customer' || empty($decoded['email']) || empty($decoded['exp'])) {
            return redirect()->route('customer.forgot-password')->with('message', 'Token tidak valid.');
        }
        $exp = \Illuminate\Support\Carbon::parse($decoded['exp']);
        if (now()->greaterThan($exp)) {
            return redirect()->route('customer.forgot-password')->with('message', 'Token telah kadaluarsa.');
        }
        if (! \Illuminate\Support\Facades\View::exists('front.customer.reset-pass')) {
            Log::error('View front.customer.reset-pass not found on server');
            return redirect()->route('customer.forgot-password')->with('message', 'Halaman reset belum tersedia. Silakan coba lagi.');
        }
        return view('front.customer.reset-pass', ['token' => $token, 'email' => $decoded['email']]);
    }

    public function performResetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        try {
            $decrypted = Crypt::decryptString($data['token']);
            $decoded = json_decode($decrypted, true);
        } catch (\Throwable $e) {
            Log::warning('Reset password decrypt failed (submit)', ['error' => $e->getMessage()]);
            return redirect()->route('customer.forgot-password')->with('message', 'Token tidak valid atau kadaluarsa.');
        }
        $exp = \Illuminate\Support\Carbon::parse($decoded['exp'] ?? null);
        if (!is_array($decoded) || ($decoded['type'] ?? '') !== 'customer' || empty($decoded['email']) || !$exp || now()->greaterThan($exp)) {
            return redirect()->route('customer.forgot-password')->with('message', 'Token tidak valid atau kadaluarsa.');
        }
        $user = User::where('email', $decoded['email'])->where('role', 'customer')->first();
        if (! $user || ! $user->is_active) {
            return redirect()->route('customer.forgot-password')->withErrors(['email' => 'Akun tidak ditemukan atau tidak aktif.']);
        }
        $user->password = Hash::make($data['password']);
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login')->with('success', 'Password berhasil direset. Silakan login kembali.');
    }

    public function mitraDashboard(): View
    {
        $mitra = \App\Models\MasterMitraBrankas::where('sys_user_id', auth()->id())->first();
        $komisiList = $mitra
            ? \App\Models\MasterMitraKomisi::where('master_mitra_brankas_id', $mitra->id)
                ->where('is_active', true)
                ->orderByDesc('id')
                ->get()
            : collect();
        $latestPrice = \App\Models\MasterGoldPrice::where('is_active', true)->orderByDesc('price_date')->first();
        $hargaPerGram = (float) ($latestPrice->price_sell ?? 0);
        $assignments = $mitra
            ? \App\Models\TransPoMitraKomisi::with('po')
                ->where('master_mitra_brankas_id', $mitra->id)
                ->orderByDesc('id')
                ->limit(10)
                ->get()
            : collect();
        $todayAllocated = $mitra
            ? (float) \App\Models\TransPoMitraKomisi::where('master_mitra_brankas_id', $mitra->id)
                ->where('tanggal_komisi', date('Y-m-d'))
                ->sum('jumlah_gram')
            : 0.0;
        $limitHarian = (float) ($mitra->harian_limit_gram ?? 0.0);
        $saldoKomisi = $mitra
            ? (float) \App\Models\TransPoMitraKomisi::where('master_mitra_brankas_id', $mitra->id)->sum('komisi_amount')
            : 0.0;
        return view('front.mitra.dashboard', compact('mitra', 'komisiList', 'hargaPerGram', 'saldoKomisi', 'assignments', 'todayAllocated', 'limitHarian'));
    }

    public function mitraKomisiIndex(): View
    {
        $mitra = \App\Models\MasterMitraBrankas::where('sys_user_id', auth()->id())->firstOrFail();
        $komisiList = \App\Models\MasterMitraKomisi::where('master_mitra_brankas_id', $mitra->id)
            ->orderByDesc('id')
            ->get();
        $assignments = \App\Models\TransPoMitraKomisi::with('po')
            ->where('master_mitra_brankas_id', $mitra->id)
            ->orderByDesc('id')
            ->limit(20)
            ->get();
        return view('front.mitra.komisi.index', compact('mitra', 'komisiList', 'assignments'));
    }

    public function mitraProfile(): View
    {
        $mitra = \App\Models\MasterMitraBrankas::where('sys_user_id', auth()->id())->firstOrFail();
        return view('front.mitra.profile', compact('mitra'));
    }

    public function updateMitraProfile(Request $request): \Illuminate\Http\RedirectResponse
    {
        $mitra = \App\Models\MasterMitraBrankas::where('sys_user_id', auth()->id())->firstOrFail();

        $rawPhone = $request->input('phone_wa');
        $p = is_string($rawPhone) ? preg_replace('/[^0-9+]/', '', $rawPhone) : null;
        if ($p !== null) {
            if (strpos($p, '+62') === 0) {
                $p = '0' . substr($p, 3);
            } elseif (strpos($p, '62') === 0) {
                $p = '0' . substr($p, 2);
            }
            $request->merge(['phone_wa' => $p]);
        }

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'phone_wa'     => ['nullable', 'string', 'max:30', 'unique:master_mitra_brankas,phone_wa,' . $mitra->id],
            'platform'     => ['nullable', 'string', 'max:50'],
            'account_no'   => ['nullable', 'string', 'max:100'],
        ]);

        $mitra->fill([
            'nama_lengkap' => $data['nama_lengkap'],
            'phone_wa'     => $data['phone_wa'] ?? $mitra->phone_wa,
            'platform'     => $data['platform'] ?? $mitra->platform,
            'account_no'   => $data['account_no'] ?? $mitra->account_no,
        ]);
        $mitra->save();

        return redirect()->route('mitra.profile')->with('success', 'Profil mitra berhasil diperbarui.');
    }
}
