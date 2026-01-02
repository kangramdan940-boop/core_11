<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class LoginManagementController extends Controller
{
    public function index(Request $request): View
    {
        $rows = DB::table('sessions')
            ->select('user_id', DB::raw('MAX(last_activity) as last_activity'), DB::raw('COUNT(*) as session_count'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('last_activity')
            ->get();

        $userIds = collect($rows)->pluck('user_id')->map(fn ($id) => (int) $id)->all();

        $users = User::query()
            ->whereIn('id', $userIds)
            ->get(['id', 'name', 'email'])
            ->keyBy('id');

        $items = collect($rows)->map(function ($row) use ($users) {
            $id = (int) $row->user_id;
            $user = $users->get($id);
            return [
                'user_id' => $id,
                'name' => $user?->name ?? '-',
                'email' => $user?->email ?? '-',
                'session_count' => (int) $row->session_count,
                'last_activity' => (int) $row->last_activity,
            ];
        });

        return view('admin.login_management.index', ['items' => $items]);
    }

    public function kick(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'min:1'],
        ]);

        $userId = (int) $data['user_id'];
        $userExists = User::whereKey($userId)->exists();

        DB::transaction(function () use ($userId, $userExists): void {
            DB::table('sessions')->where('user_id', $userId)->delete();
            if ($userExists) {
                User::whereKey($userId)->update(['remember_token' => null]);
            }
        });

        return redirect()
            ->route('admin.login-management.index')
            ->with('status', 'User telah di-logout dari semua device dan Ingat login saya dinonaktifkan.');
    }
}