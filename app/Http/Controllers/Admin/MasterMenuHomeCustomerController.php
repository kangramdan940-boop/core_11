<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterMenuHomeCustomer;
use Illuminate\Http\Request;

class MasterMenuHomeCustomerController extends Controller
{
    public function index()
    {
        $menus = MasterMenuHomeCustomer::orderByDesc('id')->paginate(20);
        return view('admin.master_menu_home_customer.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.master_menu_home_customer.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label'     => ['required', 'string', 'max:150'],
            'path_url'  => ['nullable', 'string', 'max:255'],
            'image'     => ['nullable', 'image', 'max:3072'],
            'image_url' => ['required_without:image', 'string', 'max:255'],
        ]);

        if ($request->hasFile('image')) {
            $dir = public_path('uploads/menu_home_customer');
            \Illuminate\Support\Facades\File::ensureDirectoryExists($dir);
            $file = $request->file('image');
            $filename = uniqid('menu_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $data['image'] = 'uploads/menu_home_customer/' . $filename;
        } else {
            $data['image'] = $data['image_url'];
        }

        unset($data['image_url']);

        MasterMenuHomeCustomer::create($data);

        return redirect()
            ->route('admin.master.menu-home-customer.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(MasterMenuHomeCustomer $menu)
    {
        return view('admin.master_menu_home_customer.edit', compact('menu'));
    }

    public function update(Request $request, MasterMenuHomeCustomer $menu)
    {
        $data = $request->validate([
            'label'     => ['required', 'string', 'max:150'],
            'path_url'  => ['nullable', 'string', 'max:255'],
            'image'     => ['nullable', 'image', 'max:3072'],
            'image_url' => ['required_without:image', 'string', 'max:255'],
        ]);

        if ($request->hasFile('image')) {
            $dir = public_path('uploads/menu_home_customer');
            \Illuminate\Support\Facades\File::ensureDirectoryExists($dir);
            $file = $request->file('image');
            $filename = uniqid('menu_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $data['image'] = 'uploads/menu_home_customer/' . $filename;
        } else {
            $data['image'] = $data['image_url'];
        }

        unset($data['image_url']);

        $menu->update($data);

        return redirect()
            ->route('admin.master.menu-home-customer.index')
            ->with('success', 'Menu berhasil diupdate.');
    }

    public function destroy(MasterMenuHomeCustomer $menu)
    {
        $menu->delete();

        return redirect()
            ->route('admin.master.menu-home-customer.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}