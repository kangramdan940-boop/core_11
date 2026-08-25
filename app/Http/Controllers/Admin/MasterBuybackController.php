<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterBuybackController extends Controller
{
    private const CODE = 'buyback';

    public function index()
    {
        $items = DB::table('wp_etalase_emas')
            ->where('code', self::CODE)
            ->orderByDesc('id')
            ->get(['id', 'icon', 'brand', 'berat', 'stok', 'status', 'harga', 'buyback', 'created_at', 'updated_at']);

        return view('admin.master_buyback.index', compact('items'));
    }

    public function create()
    {
        return view('admin.master_buyback.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        DB::table('wp_etalase_emas')->insert([
            'icon'       => $data['icon'] ?? null,
            'code'       => self::CODE,
            'brand'      => $data['brand'],
            'berat'      => $data['berat'],
            'stok'       => $data['stok'],
            'status'     => $data['status'],
            'harga'      => $data['harga'],
            'buyback'    => $data['buyback'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.master.buyback.index')
            ->with('success', 'Data buyback berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $item = DB::table('wp_etalase_emas')
            ->where('code', self::CODE)
            ->where('id', $id)
            ->first();

        abort_if(!$item, 404);

        return view('admin.master_buyback.edit', compact('item'));
    }

    public function update(Request $request, int $id)
    {
        $exists = DB::table('wp_etalase_emas')
            ->where('code', self::CODE)
            ->where('id', $id)
            ->exists();

        abort_if(!$exists, 404);

        $data = $this->validateData($request);

        DB::table('wp_etalase_emas')
            ->where('id', $id)
            ->update([
                'icon'       => $data['icon'] ?? null,
                'brand'      => $data['brand'],
                'berat'      => $data['berat'],
                'stok'       => $data['stok'],
                'status'     => $data['status'],
                'harga'      => $data['harga'],
                'buyback'    => $data['buyback'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.master.buyback.index')
            ->with('success', 'Data buyback berhasil diupdate.');
    }

    public function destroy(int $id)
    {
        DB::table('wp_etalase_emas')
            ->where('code', self::CODE)
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('admin.master.buyback.index')
            ->with('success', 'Data buyback berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'icon'    => ['nullable', 'string', 'max:255'],
            'brand'   => ['required', 'string', 'max:255'],
            'berat'   => ['required', 'string', 'max:50'],
            'stok'    => ['required', 'string', 'max:50'],
            'status'  => ['required', 'string', 'max:50'],
            'harga'   => ['required', 'integer', 'min:0'],
            'buyback' => ['required', 'integer', 'min:0'],
        ]);
    }
}
