<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterFlashSale;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MasterFlashSaleController extends Controller
{
    public function index(): View
    {
        $items = MasterFlashSale::orderByDesc('id')->get();
        return view('admin.master_flash_sale.index', compact('items'));
    }

    public function create(): View
    {
        $item = new MasterFlashSale();
        return view('admin.master_flash_sale.create', compact('item'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'item_name'   => ['required','string','max:150'],
            'harga_jual'  => ['required','numeric','min:0'],
            'tahun'       => ['nullable','integer'],
            'periode'     => ['nullable','string','max:50'],
            'harga_modal' => ['nullable','numeric','min:0'],
        ]);

        MasterFlashSale::create($data);
        return redirect()->route('admin.master.flash-sales.index')->with('success', 'Flash Sale berhasil ditambahkan.');
    }

    public function edit(MasterFlashSale $flashSale): View
    {
        $item = $flashSale;
        return view('admin.master_flash_sale.edit', compact('item'));
    }

    public function update(Request $request, MasterFlashSale $flashSale): RedirectResponse
    {
        $data = $request->validate([
            'item_name'   => ['required','string','max:150'],
            'harga_jual'  => ['required','numeric','min:0'],
            'tahun'       => ['nullable','integer'],
            'periode'     => ['nullable','string','max:50'],
            'harga_modal' => ['nullable','numeric','min:0'],
        ]);

        $flashSale->update($data);
        return redirect()->route('admin.master.flash-sales.index')->with('success', 'Flash Sale berhasil diupdate.');
    }

    public function destroy(MasterFlashSale $flashSale): RedirectResponse
    {
        $flashSale->delete();
        return redirect()->route('admin.master.flash-sales.index')->with('success', 'Flash Sale dihapus.');
    }
}