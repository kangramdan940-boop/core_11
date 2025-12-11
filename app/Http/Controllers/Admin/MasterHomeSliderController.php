<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterHomeSlider;
use Illuminate\Http\Request;

class MasterHomeSliderController extends Controller
{
    public function index()
    {
        $sliders = MasterHomeSlider::orderByDesc('id')->get();
        return view('admin.master_home_slider.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.master_home_slider.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'max:3072'],
            'image_url'   => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('image')) {
            $dir = public_path('uploads/home_slider');
            \Illuminate\Support\Facades\File::ensureDirectoryExists($dir);
            $file = $request->file('image');
            $filename = uniqid('slider_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $data['image_url'] = 'uploads/home_slider/' . $filename;
        }

        MasterHomeSlider::create($data);

        return redirect()
            ->route('admin.master.home-slider.index')
            ->with('success', 'Slider berhasil ditambahkan.');
    }

    public function edit(MasterHomeSlider $slider)
    {
        return view('admin.master_home_slider.edit', compact('slider'));
    }

    public function update(Request $request, MasterHomeSlider $slider)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'max:3072'],
            'image_url'   => ['required_without:image', 'string', 'max:255'],
        ]);

        if ($request->hasFile('image')) {
            $dir = public_path('uploads/home_slider');
            \Illuminate\Support\Facades\File::ensureDirectoryExists($dir);
            $file = $request->file('image');
            $filename = uniqid('slider_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $data['image_url'] = 'uploads/home_slider/' . $filename;
        }

        $slider->update($data);

        return redirect()
            ->route('admin.master.home-slider.index')
            ->with('success', 'Slider berhasil diupdate.');
    }

    public function destroy(MasterHomeSlider $slider)
    {
        $slider->delete();

        return redirect()
            ->route('admin.master.home-slider.index')
            ->with('success', 'Slider berhasil dihapus.');
    }
}