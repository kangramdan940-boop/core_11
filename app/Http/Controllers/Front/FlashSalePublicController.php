<?php
declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\MasterFlashSale;
use App\Models\TransFlashSaleOrder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FlashSalePublicController extends Controller
{
    public function showForm(MasterFlashSale $flashSale, string $enc, string $phone, string $eenc, string $qenc): View
    {
        $prefilledPhone = preg_replace('/[^0-9+]/', '', $phone ?? '');
        try {
            $periode = Crypt::decryptString($enc);
        } catch (\Throwable $e) {
            abort(404);
        }
        if ((string)($flashSale->periode ?? '') !== (string)$periode) {
            abort(404);
        }
        try { $qtyLimit = (int) Crypt::decryptString($qenc); } catch (\Throwable $e) { $qtyLimit = null; }
        try { $expTs = (int) Crypt::decryptString($eenc); } catch (\Throwable $e) { $expTs = 0; }
        if ($expTs && Carbon::now()->timestamp > $expTs) { abort(404); }
        $payCode = $this->generatePayCode();
        $linkKey = sha1('single:'.$flashSale->id.':'.$enc.':'.$prefilledPhone.':'.$eenc.':'.$qenc);
        if (DB::table('flash_sale_link_usages')->where('link_key',$linkKey)->exists()) { abort(404); }
        return view('front.flash_sale.form', [
            'flashSale' => $flashSale,
            'enc' => $enc,
            'eenc' => $eenc,
            'qenc' => $qenc,
            'qtyLimit' => $qtyLimit,
            'expiresAt' => $expTs ? Carbon::createFromTimestamp($expTs) : null,
            'phone' => $prefilledPhone,
            'payCode' => $payCode,
        ]);
    }

    public function store(Request $request, MasterFlashSale $flashSale, string $enc, string $phone, string $eenc, string $qenc): RedirectResponse
    {
        $prefilledPhone = preg_replace('/[^0-9+]/', '', $phone ?? '');

        $data = $request->validate([
            'customer_name'    => ['nullable','string','max:150'],
            'shipping_address' => ['nullable','string'],
            'payment_proof'    => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
            'package_proof'    => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
            'pay_code'         => ['required','integer','between:1,999'],
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

        $qtyLimit = null;
        try { $qtyLimit = (int) Crypt::decryptString($qenc); } catch (\Throwable $e) {}
        $expTs = 0; try { $expTs = (int) Crypt::decryptString($eenc); } catch (\Throwable $e) {}
        if ($expTs && Carbon::now()->timestamp > $expTs) { return redirect()->route('public.flash-sale.show', [$flashSale->id, $enc, $phone, $eenc, $qenc])->with('success', 'Link kadaluarsa.'); }
        TransFlashSaleOrder::create([
            'customer_name'        => $data['customer_name'] ?? null,
            'phone'                => $prefilledPhone ?: null,
            'master_flash_sale_id' => (int) $flashSale->id,
            'shipping_address'     => $data['shipping_address'] ?? null,
            'payment_proof_url'    => $paymentUrl,
            'package_proof_url'    => $packageUrl,
            'qty'                  => $qtyLimit,
            'pay_code'             => $data['pay_code'],
            'created_by'           => null,
        ]);
        $linkKey = sha1('single:'.$flashSale->id.':'.$enc.':'.$prefilledPhone.':'.$eenc.':'.$qenc);
        DB::table('flash_sale_link_usages')->insert(['link_key'=>$linkKey,'created_at'=>now()]);
        return redirect()->route('public.flash-sale.success')->with('success', 'Transaksi Flash Sale berhasil dibuat.');
    }

    public function showSelect(string $phone, string $eenc, string $qenc, string $benc): View
    {
        $prefilledPhone = preg_replace('/[^0-9+]/', '', $phone ?? '');
        $items = MasterFlashSale::select('id','item_name','harga_jual')->orderBy('item_name')->get();
        try { $qtyLimit = (int) Crypt::decryptString($qenc); } catch (\Throwable $e) { $qtyLimit = null; }
        $expTs = 0; try { $expTs = (int) Crypt::decryptString($eenc); } catch (\Throwable $e) {}
        if ($expTs && Carbon::now()->timestamp > $expTs) { abort(404); }
        $bankInfo = null; try { $bankInfo = Crypt::decryptString($benc); } catch (\Throwable $e) {}
        $payCode = $this->generatePayCode();
        $linkKey = sha1('select:'.$prefilledPhone.':'.$eenc.':'.$qenc.':'.$benc);
        if (DB::table('flash_sale_link_usages')->where('link_key',$linkKey)->exists()) { abort(404); }
        return view('front.flash_sale.select', [
            'items' => $items,
            'phone' => $prefilledPhone,
            'eenc' => $eenc,
            'qenc' => $qenc,
            'benc' => $benc,
            'qtyLimit' => $qtyLimit,
            'expiresAt' => $expTs ? Carbon::createFromTimestamp($expTs) : null,
            'payCode' => $payCode,
            'bankInfo' => $bankInfo,
        ]);
    }

    public function storeSelect(Request $request, string $phone, string $eenc, string $qenc, string $benc): RedirectResponse
    {
        $prefilledPhone = preg_replace('/[^0-9+]/', '', $phone ?? '');
        $data = $request->validate([
            'flash_sale_id'    => ['required','integer','exists:master_flash_sales,id'],
            'customer_name'    => ['nullable','string','max:150'],
            'shipping_address' => ['nullable','string'],
            'payment_proof'    => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
            'package_proof'    => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
            'pay_code'         => ['required','integer','between:1,999'],
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
        $qtyLimit = null;
        try { $qtyLimit = (int) Crypt::decryptString($qenc); } catch (\Throwable $e) {}
        $expTs = 0; try { $expTs = (int) Crypt::decryptString($eenc); } catch (\Throwable $e) {}
        if ($expTs && Carbon::now()->timestamp > $expTs) { return redirect()->route('public.flash-sale.select', [$prefilledPhone, $eenc, $qenc, $benc])->with('success', 'Link kadaluarsa.'); }
        TransFlashSaleOrder::create([
            'customer_name'        => $data['customer_name'] ?? null,
            'phone'                => $prefilledPhone ?: null,
            'master_flash_sale_id' => (int) $data['flash_sale_id'],
            'shipping_address'     => $data['shipping_address'] ?? null,
            'payment_proof_url'    => $paymentUrl,
            'package_proof_url'    => $packageUrl,
            'qty'                  => $qtyLimit,
            'pay_code'             => $data['pay_code'],
            'created_by'           => null,
        ]);
        $linkKey = sha1('select:'.$prefilledPhone.':'.$eenc.':'.$qenc.':'.$benc);
        DB::table('flash_sale_link_usages')->insert(['link_key'=>$linkKey,'created_at'=>now()]);
        return redirect()->route('public.flash-sale.success')->with('success', 'Transaksi Flash Sale berhasil dibuat.');
    }
    protected function generatePayCode(): int
    {
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
        $used = DB::table('trans_flash_sale_orders')
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->pluck('pay_code')
            ->filter()
            ->toArray();
        for ($i = 101; $i <= 999; $i++) { if (!in_array($i, $used, true)) return $i; }
        return random_int(100, 999);
    }

    public function success(): View
    {
        return view('front.flash_sale.success');
    }
}