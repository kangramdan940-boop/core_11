<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPaymentSetting;
use Illuminate\Http\Request;

class MasterPaymentSettingController extends Controller
{
    public function index()
    {
        $setting = MasterPaymentSetting::orderByDesc('id')->first();
        return view('admin.master_payment_setting.index', compact('setting'));
    }

    public function create()
    {
        $existing = MasterPaymentSetting::first();
        if ($existing) {
            return redirect()->route('admin.master.payment-settings.edit', $existing);
        }
        return view('admin.master_payment_setting.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rekening_nomor' => ['required', 'string', 'max:50'],
            'bank_nama' => ['required', 'string', 'max:100'],
            'rekening_atas_nama' => ['required', 'string', 'max:150'],
            'expired_minutes' => ['required', 'integer', 'min:1'],
            'konfirmasi_petunjuk' => ['nullable', 'string'],
            'syarat_ketentuan' => ['nullable', 'string'],
            'jasa_titip_informasi' => ['nullable', 'string'],
        ]);

        MasterPaymentSetting::create($data);

        return redirect()
            ->route('admin.master.payment-settings.index')
            ->with('success', 'Konfigurasi pembayaran berhasil disimpan.');
    }

    public function edit(MasterPaymentSetting $payment)
    {
        return view('admin.master_payment_setting.edit', ['setting' => $payment]);
    }

    public function update(Request $request, MasterPaymentSetting $payment)
    {
        $data = $request->validate([
            'rekening_nomor' => ['required', 'string', 'max:50'],
            'bank_nama' => ['required', 'string', 'max:100'],
            'rekening_atas_nama' => ['required', 'string', 'max:150'],
            'expired_minutes' => ['required', 'integer', 'min:1'],
            'konfirmasi_petunjuk' => ['nullable', 'string'],
            'syarat_ketentuan' => ['nullable', 'string'],
            'jasa_titip_informasi' => ['nullable', 'string'],
        ]);

        $payment->update($data);

        return redirect()
            ->route('admin.master.payment-settings.index')
            ->with('success', 'Konfigurasi pembayaran berhasil diupdate.');
    }

    public function destroy(MasterPaymentSetting $payment)
    {
        $payment->delete();

        return redirect()
            ->route('admin.master.payment-settings.index')
            ->with('success', 'Konfigurasi pembayaran berhasil dihapus.');
    }
}