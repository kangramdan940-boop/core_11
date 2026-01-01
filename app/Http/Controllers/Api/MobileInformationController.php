<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterMobileAppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MobileInformationController extends Controller
{
    public function show(Request $request)
    {
        $config = MasterMobileAppConfig::orderByDesc('id')->first();
        $data = $this->mapConfig($config);
        $updatedAt = $config ? ($config->updated_at ? $config->updated_at->toIso8601String() : null) : null;

        return response()->json([
            'status' => true,
            'data' => $data,
            'meta' => ['updatedAt' => $updatedAt]
        ]);
    }

    private function mapConfig(?MasterMobileAppConfig $config): array
    {
        if (!$config) {
            return [
                'loginPageIcon' => null,
                'informationLink' => null,
                'developmentMode' => null,
                'statusNaik' => null,
                'statusTurun' => null,
                'welcomeTitle' => null,
                'welcomeDescription' => null,
                'broadcastInfoBannerStatus' => null,
                'broadcastInfoBannerDescription' => null,
            ];
        }

        return [
            'loginPageIcon' => $this->resolveUrl($config->login_page_icon),
            'informationLink' => $config->information_link ?: null,
            'developmentMode' => $this->toNullableBool($config->getRawOriginal('development_mode')),
            'statusNaik' => $this->toNullableBool($config->getRawOriginal('status_naik')),
            'statusTurun' => $this->toNullableBool($config->getRawOriginal('status_turun')),
            'welcomeTitle' => $config->welcome_title ?: null,
            'welcomeDescription' => $config->welcome_description ?: null,
            'broadcastInfoBannerStatus' => $this->toNullableBool($config->getRawOriginal('broadcast_info_banner_status')),
            'broadcastInfoBannerDescription' => $config->broadcast_info_banner_description ?: null,
        ];
    }

    private function resolveUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (Str::startsWith($path, ['http://','https://','/'])) return $path;
        return asset(ltrim($path, '/'));
    }

    private function toNullableBool($val): ?bool
    {
        return $val === null ? null : (bool) $val;
    }
}