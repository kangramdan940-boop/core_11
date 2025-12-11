<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterGoldPrice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterGoldPriceController extends Controller
{
    public function index()
    {
        $prices = MasterGoldPrice::orderByDesc('price_date')
            ->orderBy('source')
            ->get();

        return view('admin.master_gold_price.index', compact('prices'));
    }

    public function create()
    {
        return view('admin.master_gold_price.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'price_date'    => ['required', 'date'],
            'source'        => [
                'required', 'string', 'max:50',
                Rule::unique('master_gold_price')->where(
                    fn ($q) => $q->where('price_date', $request->price_date)
                ),
            ],
            'price_buy'     => ['required', 'numeric', 'min:0'],
            'price_sell'    => ['required', 'numeric', 'min:0'],
            'price_buyback' => ['nullable', 'numeric', 'min:0'],
            'note'          => ['nullable', 'string'],
            'is_active'     => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

        MasterGoldPrice::create($data);

        return redirect()
            ->route('admin.master.gold-prices.index')
            ->with('success', 'Harga emas berhasil ditambahkan.');
    }

    public function edit(MasterGoldPrice $price)
    {
        return view('admin.master_gold_price.edit', compact('price'));
    }

    public function update(Request $request, MasterGoldPrice $price)
    {
        $data = $request->validate([
            'price_date'    => ['required', 'date'],
            'source'        => [
                'required', 'string', 'max:50',
                Rule::unique('master_gold_price')
                    ->ignore($price->id)
                    ->where(fn ($q) => $q->where('price_date', $request->price_date)),
            ],
            'price_buy'     => ['required', 'numeric', 'min:0'],
            'price_sell'    => ['required', 'numeric', 'min:0'],
            'price_buyback' => ['nullable', 'numeric', 'min:0'],
            'note'          => ['nullable', 'string'],
            'is_active'     => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');

        $price->update($data);

        return redirect()
            ->route('admin.master.gold-prices.index')
            ->with('success', 'Harga emas berhasil diupdate.');
    }

    public function destroy(MasterGoldPrice $price)
    {
        $price->delete();

        return redirect()
            ->route('admin.master.gold-prices.index')
            ->with('success', 'Harga emas berhasil dihapus.');
    }
}