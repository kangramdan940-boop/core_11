<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterSettingController extends Controller
{
    public function index()
    {
        $settings = MasterSetting::orderByDesc('id')->get();
        return view('admin.master_setting.index', compact('settings'));
    }

    public function create()
    {
        return view('admin.master_setting.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key'       => ['required', 'string', 'max:100', 'unique:master_setting,key'],
            'value'     => ['nullable', 'string'],
            'label'     => ['nullable', 'string', 'max:150'],
            'group'     => ['nullable', 'string', 'max:50'],
            'is_active' => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

        MasterSetting::create($data);

        return redirect()
            ->route('admin.master.settings.index')
            ->with('success', 'Setting berhasil ditambahkan.');
    }

    public function edit(MasterSetting $setting)
    {
        return view('admin.master_setting.edit', compact('setting'));
    }

    public function update(Request $request, MasterSetting $setting)
    {
        $data = $request->validate([
            'key'       => ['required', 'string', 'max:100', Rule::unique('master_setting', 'key')->ignore($setting->id)],
            'value'     => ['nullable', 'string'],
            'label'     => ['nullable', 'string', 'max:150'],
            'group'     => ['nullable', 'string', 'max:50'],
            'is_active' => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

        $setting->update($data);

        return redirect()
            ->route('admin.master.settings.index')
            ->with('success', 'Setting berhasil diupdate.');
    }

    public function destroy(MasterSetting $setting)
    {
        $setting->delete();

        return redirect()
            ->route('admin.master.settings.index')
            ->with('success', 'Setting berhasil dihapus.');
    }
}