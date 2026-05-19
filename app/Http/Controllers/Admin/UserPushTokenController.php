<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserPushToken;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserPushTokenController extends Controller
{
    public function index(Request $request): View
    {
        $query = UserPushToken::query()->with('user')->orderByDesc('id');

        if ($q = trim($request->string('q')->toString())) {
            $query->where(function ($qb) use ($q) {
                $qb->where('expo_push_token', 'like', "%{$q}%")
                    ->orWhere('device_name', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        if ($request->has('is_active') && $request->string('is_active')->toString() !== '') {
            $query->where('is_active', $request->string('is_active')->toString() === '1');
        }

        if ($platform = $request->string('platform')->toString()) {
            $query->where('platform', $platform);
        }

        $items = $query->paginate(20)->withQueryString();

        return view('admin.push_tokens.index', compact('items'));
    }

    public function destroy(UserPushToken $pushToken): RedirectResponse
    {
        $pushToken->delete();

        return redirect()
            ->route('admin.push-tokens.index')
            ->with('status', 'Push token berhasil dihapus.');
    }

    public function toggleActive(UserPushToken $pushToken): RedirectResponse
    {
        $pushToken->update(['is_active' => !$pushToken->is_active]);

        $status = $pushToken->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.push-tokens.index')
            ->with('status', "Push token berhasil {$status}.");
    }
}
