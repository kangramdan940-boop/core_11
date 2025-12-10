<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterMitraKomisi;
use App\Models\MasterMitraBrankas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterMitraKomisiController extends Controller
{
    public function index()
    {
        $komisis = MasterMitraKomisi::with('mitra')
            ->orderByDesc('id')
            ->get();

        $start = now()->startOfMonth()->toDateString();
        $end = now()->endOfMonth()->toDateString();
        $monthSummaries = \App\Models\TransPoMitraKomisi::selectRaw('master_mitra_brankas_id, SUM(komisi_amount) as total')
            ->whereBetween('tanggal_komisi', [$start, $end])
            ->groupBy('master_mitra_brankas_id')
            ->pluck('total', 'master_mitra_brankas_id');

        return view('admin.master_mitra_komisi.index', compact('komisis', 'monthSummaries'));
    }

    public function create()
    {
        $mitras = MasterMitraBrankas::orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'kode_mitra']);
        return view('admin.master_mitra_komisi.create', compact('mitras'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'master_mitra_brankas_id' => ['nullable', 'integer', 'exists:master_mitra_brankas,id'],
            'tipe_transaksi'          => ['required', 'string', 'max:30'],
            'komisi_persen'           => ['required', 'numeric', 'min:0', 'max:100'],
            'berlaku_mulai'           => ['nullable', 'date'],
            'berlaku_sampai'          => ['nullable', 'date'],
            'is_active'               => ['sometimes', 'accepted'],
            'catatan'                 => ['nullable', 'string'],
        ]);

        if ($data['berlaku_mulai'] && $data['berlaku_sampai']) {
            // Simple guard jika tanggal berhurutan salah
            abort_if($data['berlaku_sampai'] < $data['berlaku_mulai'], 422, 'Tanggal berlaku sampai tidak boleh sebelum berlaku mulai.');
        }

        $data['is_active'] = $request->has('is_active');

        MasterMitraKomisi::create($data);

        return redirect()
            ->route('admin.master.mitra-komisi.index')
            ->with('success', 'Komisi mitra berhasil ditambahkan.');
    }

    public function edit(MasterMitraKomisi $komisi)
    {
        $mitras = MasterMitraBrankas::orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'kode_mitra']);
        return view('admin.master_mitra_komisi.edit', compact('komisi', 'mitras'));
    }

    public function update(Request $request, MasterMitraKomisi $komisi)
    {
        $data = $request->validate([
            'master_mitra_brankas_id' => ['nullable', 'integer', 'exists:master_mitra_brankas,id'],
            'tipe_transaksi'          => ['required', 'string', 'max:30'],
            'komisi_persen'           => ['required', 'numeric', 'min:0', 'max:100'],
            'berlaku_mulai'           => ['nullable', 'date'],
            'berlaku_sampai'          => ['nullable', 'date'],
            'is_active'               => ['sometimes', 'accepted'],
            'catatan'                 => ['nullable', 'string'],
        ]);

        if ($data['berlaku_mulai'] && $data['berlaku_sampai']) {
            abort_if($data['berlaku_sampai'] < $data['berlaku_mulai'], 422, 'Tanggal berlaku sampai tidak boleh sebelum berlaku mulai.');
        }

        $data['is_active'] = $request->has('is_active');

        $komisi->update($data);

        return redirect()
            ->route('admin.master.mitra-komisi.index')
            ->with('success', 'Komisi mitra berhasil diupdate.');
    }

    public function destroy(MasterMitraKomisi $komisi)
    {
        $komisi->delete();

        return redirect()
            ->route('admin.master.mitra-komisi.index')
            ->with('success', 'Komisi mitra berhasil dihapus.');
    }
}