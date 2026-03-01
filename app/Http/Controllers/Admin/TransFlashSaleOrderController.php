<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransFlashSaleOrder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class TransFlashSaleOrderController extends Controller
{
    public function index(): View
    {
        $orders = TransFlashSaleOrder::with('flashSale')->orderByDesc('id')->get();
        return view('admin.trans_flash_sale.index', compact('orders'));
    }

    public function create(): View
    {
        $items = \App\Models\MasterFlashSale::select('id', 'item_name')->orderBy('item_name')->get();
        return view('admin.trans_flash_sale.create', compact('items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_name'    => ['nullable','string','max:150'],
            'phone'            => ['nullable','string','max:30'],
            'flash_sale_id'    => ['nullable','integer','exists:master_flash_sales,id'],
            'shipping_address' => ['nullable','string'],
            'payment_proof'    => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
            'package_proof'    => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        ]);

        $paymentUrl = null;
        if ($request->hasFile('payment_proof')) {
            $dir = public_path('uploads/flash_sale/payment_proofs');
            File::ensureDirectoryExists($dir);
            $f = $request->file('payment_proof');
            $name = 'pay_' . uniqid('fs_', true) . '.' . strtolower($f->getClientOriginalExtension() ?: 'dat');
            $f->move($dir, $name);
            $paymentUrl = 'uploads/flash_sale/payment_proofs/' . $name;
        }

        $packageUrl = null;
        if ($request->hasFile('package_proof')) {
            $dir = public_path('uploads/flash_sale/package_proofs');
            File::ensureDirectoryExists($dir);
            $f = $request->file('package_proof');
            $name = 'pack_' . uniqid('fs_', true) . '.' . strtolower($f->getClientOriginalExtension() ?: 'dat');
            $f->move($dir, $name);
            $packageUrl = 'uploads/flash_sale/package_proofs/' . $name;
        }

        TransFlashSaleOrder::create([
            'customer_name'        => $data['customer_name'] ?? null,
            'phone'                => $data['phone'] ?? null,
            'master_flash_sale_id' => $data['flash_sale_id'] ?? null,
            'shipping_address'     => $data['shipping_address'] ?? null,
            'payment_proof_url'    => $paymentUrl,
            'package_proof_url'    => $packageUrl,
            'qty'                  => null,
            'created_by'           => (int) ($request->user()?->id ?? 0),
        ]);

        return redirect()->route('admin.trans.flash-sale-orders.index')->with('success', 'Transaksi Flash Sale berhasil dibuat.');
    }

    public function show(TransFlashSaleOrder $order): View
    {
        return view('admin.trans_flash_sale.show', compact('order'));
    }

    public function destroy(TransFlashSaleOrder $order): RedirectResponse
    {
        $order->delete();
        return redirect()->route('admin.trans.flash-sale-orders.index')->with('success', 'Transaksi Flash Sale dihapus.');
    }
}