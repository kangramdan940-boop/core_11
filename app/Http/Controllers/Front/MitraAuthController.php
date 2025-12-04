<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class MitraAuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('front.mitra.login');
    }

    public function showRegisterForm(): View
    {
        return view('front.mitra.register');
    }
}