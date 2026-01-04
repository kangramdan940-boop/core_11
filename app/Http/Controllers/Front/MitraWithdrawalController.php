<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\MitraWithdrawal;
use App\Models\TransPoMitraKomisi;
use App\Models\MasterMitraBrankas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class MitraWithdrawalController extends Controller
{
    public function create(): View
    {
        $mitra = MasterMitraBrankas::where('sys_user_id', auth()->id())->firstOrFail();

        $saldoKomisi = (float) TransPoMitraKomisi::where('master_mitra_brankas_id', $mitra->id)
            ->sum('komisi_amount');

        $wdPendingAmount = (float) MitraWithdrawal::where('master_mitra_brankas_id', $mitra->id)
            ->whereIn('status', [MitraWithdrawal::STATUS_REQUESTED, MitraWithdrawal::STATUS_PROCESSING, MitraWithdrawal::STATUS_CHECKING])
            ->sum('amount');

        $wdCompletedAmount = (float) MitraWithdrawal::where('master_mitra_brankas_id', $mitra->id)
            ->where('status', MitraWithdrawal::STATUS_COMPLETED)
            ->sum('amount');

        $availableAmount = max(0.0, $saldoKomisi - $wdPendingAmount - $wdCompletedAmount);

        $latestWithdrawal = MitraWithdrawal::where('master_mitra_brankas_id', $mitra->id)
            ->orderByDesc('id')
            ->first();

        $withdrawals = MitraWithdrawal::where('master_mitra_brankas_id', $mitra->id)
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        return view('front.mitra.withdrawals.create', compact(
            'mitra',
            'saldoKomisi',
            'wdPendingAmount',
            'wdCompletedAmount',
            'availableAmount',
            'latestWithdrawal',
            'withdrawals'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $mitra = MasterMitraBrankas::where('sys_user_id', auth()->id())->firstOrFail();

        if (!$mitra->is_active) {
            return redirect()->back()->with('error', 'Akun Mitra belum aktif. Tidak dapat melakukan WD.');
        }

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
        ]);

        $saldoKomisi = (float) TransPoMitraKomisi::where('master_mitra_brankas_id', $mitra->id)
            ->sum('komisi_amount');

        $wdPendingAmount = (float) MitraWithdrawal::where('master_mitra_brankas_id', $mitra->id)
            ->whereIn('status', [MitraWithdrawal::STATUS_REQUESTED, MitraWithdrawal::STATUS_PROCESSING, MitraWithdrawal::STATUS_CHECKING])
            ->sum('amount');

        $wdCompletedAmount = (float) MitraWithdrawal::where('master_mitra_brankas_id', $mitra->id)
            ->where('status', MitraWithdrawal::STATUS_COMPLETED)
            ->sum('amount');

        $availableAmount = max(0.0, $saldoKomisi - $wdPendingAmount - $wdCompletedAmount);

        $amount = (float) $data['amount'];
        if ($amount > $availableAmount) {
            return redirect()->back()->withInput()->with('error', 'Nominal WD melebihi saldo tersedia.');
        }

        MitraWithdrawal::create([
            'master_mitra_brankas_id' => $mitra->id,
            'amount'                  => $amount,
            'status'                  => MitraWithdrawal::STATUS_REQUESTED,
            'target_account_no'       => $mitra->account_no,
            'requested_at'            => now(),
        ]);

        return redirect()->route('mitra.dashboard')->with('success', 'Request WD berhasil dikirim.');
    }
}