<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterPaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingApiController extends Controller
{
    public function show(Request $request)
    {
        $setting = MasterPaymentSetting::orderByDesc('id')->first();
        $data = $this->mapSetting($setting);
        $updatedAt = $setting ? ($setting->updated_at ? $setting->updated_at->toIso8601String() : null) : null;

        return response()->json([
            'status' => true,
            'data' => $data,
            'meta' => ['updatedAt' => $updatedAt],
        ]);
    }

    private function mapSetting(?MasterPaymentSetting $setting): array
    {
        if (!$setting) {
            return [
                'accountNumber' => null,
                'bankName' => null,
                'accountHolder' => null,
                'expiredMinutes' => null,
                'confirmPaymentGuide' => null,
                'termsAndConditions' => null,
                'jasaTitipInformation' => null,
            ];
        }

        return [
            'accountNumber' => $setting->rekening_nomor,
            'bankName' => $setting->bank_nama,
            'accountHolder' => $setting->rekening_atas_nama,
            'expiredMinutes' => (int) $setting->expired_minutes,
            'confirmPaymentGuide' => $setting->konfirmasi_petunjuk ?: null,
            'termsAndConditions' => $setting->syarat_ketentuan ?: null,
            'jasaTitipInformation' => $setting->jasa_titip_informasi ?: null,
        ];
    }
}