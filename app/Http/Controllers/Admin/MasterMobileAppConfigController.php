<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterMobileAppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MasterMobileAppConfigController extends Controller
{
    public function index()
    {
        $configs = MasterMobileAppConfig::orderByDesc('id')->get();
        return view('admin.master_mobile_app_configs.index', compact('configs'));
    }

    public function create()
    {
        return view('admin.master_mobile_app_configs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'login_icon' => ['nullable', 'image', 'max:3072'],
            'login_page_icon' => ['required_without:login_icon', 'string', 'max:255'],
            'information_link' => ['nullable', 'string', 'max:255'],
            'development_mode' => ['nullable', 'boolean'],
            'status_naik' => ['nullable', 'boolean'],
            'status_turun' => ['nullable', 'boolean'],
            'welcome_title' => ['nullable', 'string', 'max:150'],
            'welcome_description' => ['nullable', 'string'],
            'broadcast_info_banner_status' => ['nullable', 'boolean'],
            'broadcast_info_banner_description' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('login_icon')) {
            $dir = public_path('uploads/mobile_app');
            File::ensureDirectoryExists($dir);
            $file = $request->file('login_icon');
            $filename = uniqid('login_icon_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $data['login_page_icon'] = 'uploads/mobile_app/' . $filename;
        }

        MasterMobileAppConfig::create($data);

        return redirect()
            ->route('admin.master.mobile-app-configs.index')
            ->with('success', 'Konfigurasi berhasil ditambahkan.');
    }

    public function edit(MasterMobileAppConfig $config)
    {
        return view('admin.master_mobile_app_configs.edit', compact('config'));
    }

    public function update(Request $request, MasterMobileAppConfig $config)
    {
        $data = $request->validate([
            'login_icon' => ['nullable', 'image', 'max:3072'],
            'login_page_icon' => ['required_without:login_icon', 'string', 'max:255'],
            'information_link' => ['nullable', 'string', 'max:255'],
            'development_mode' => ['nullable', 'boolean'],
            'status_naik' => ['nullable', 'boolean'],
            'status_turun' => ['nullable', 'boolean'],
            'welcome_title' => ['nullable', 'string', 'max:150'],
            'welcome_description' => ['nullable', 'string'],
            'broadcast_info_banner_status' => ['nullable', 'boolean'],
            'broadcast_info_banner_description' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('login_icon')) {
            $dir = public_path('uploads/mobile_app');
            File::ensureDirectoryExists($dir);
            $file = $request->file('login_icon');
            $filename = uniqid('login_icon_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $data['login_page_icon'] = 'uploads/mobile_app/' . $filename;
        }

        $config->update($data);

        return redirect()
            ->route('admin.master.mobile-app-configs.index')
            ->with('success', 'Konfigurasi berhasil diupdate.');
    }

    public function destroy(MasterMobileAppConfig $config)
    {
        $config->delete();

        return redirect()
            ->route('admin.master.mobile-app-configs.index')
            ->with('success', 'Konfigurasi berhasil dihapus.');
    }
}