<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransCicilanEmas;
use App\Models\MasterLayananEmasCicilan;
use App\Models\MasterAgen;
use App\Models\MasterGramasiEmas;
use Illuminate\Http\Request;

class TransCicilanEmasController extends Controller
{
    public function index()
    {
        $items = TransCicilanEmas::with(['layanan', 'agen', 'gramasi'])->orderByDesc('id')->get();
        return view('admin.trans_cicilan_emas.index', compact('items'));
    }

    public function create()
    {
        $masters = MasterLayananEmasCicilan::where('is_active', true)->orderBy('nama_layanan')->get();
        $agens = MasterAgen::where('is_active', true)->orderBy('name')->get();
        $gramasis = MasterGramasiEmas::where('is_active', true)->orderBy('gramasi')->get();
        return view('admin.trans_cicilan_emas.create', compact('masters', 'agens', 'gramasis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'master_layanan_emas_cicilan_id' => ['required', 'integer', 'exists:master_layanan_emas_cicilan,id'],
            'master_agen_id' => ['nullable', 'integer', 'exists:master_agen,id'],
            'master_gramasi_emas_id' => ['required', 'integer', 'exists:master_gramasi_emas,id'],
            'jumlah_keping_dibuka' => ['required', 'integer', 'min:1'],
            'total_gram_dibuka' => ['required', 'numeric', 'min:0'],
        ]);

        TransCicilanEmas::create($data);

        return redirect()->route('admin.trans.cicilan-emas.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(TransCicilanEmas $record)
    {
        $masters = MasterLayananEmasCicilan::where('is_active', true)->orderBy('nama_layanan')->get();
        $agens = MasterAgen::where('is_active', true)->orderBy('name')->get();
        $gramasis = MasterGramasiEmas::where('is_active', true)->orderBy('gramasi')->get();
        return view('admin.trans_cicilan_emas.edit', compact('record', 'masters', 'agens', 'gramasis'));
    }

    public function update(Request $request, TransCicilanEmas $record)
    {
        $data = $request->validate([
            'master_layanan_emas_cicilan_id' => ['required', 'integer', 'exists:master_layanan_emas_cicilan,id'],
            'master_agen_id' => ['nullable', 'integer', 'exists:master_agen,id'],
            'master_gramasi_emas_id' => ['required', 'integer', 'exists:master_gramasi_emas,id'],
            'jumlah_keping_dibuka' => ['required', 'integer', 'min:1'],
            'total_gram_dibuka' => ['required', 'numeric', 'min:0'],
        ]);

        $record->update($data);

        return redirect()->route('admin.trans.cicilan-emas.index')->with('success', 'Data berhasil diupdate.');
    }

    public function destroy(TransCicilanEmas $record)
    {
        $record->delete();

        return redirect()->route('admin.trans.cicilan-emas.index')->with('success', 'Data berhasil dihapus.');
    }
}