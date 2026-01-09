<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterFaktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class MasterFakturKomisiController extends Controller
{
    public function store(Request $request, MasterFaktur $document)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'id_mitra' => ['nullable', 'integer', 'exists:master_mitra_brankas,id'],
            'harga_yang_dibayar' => ['required', 'numeric', 'min:0'],
            'total_komisi' => ['required', 'numeric', 'min:0'],
            'file_struk_pembayaran' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:51200'],
            'file_struk_komisi' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:51200'],
        ]);

        $existing = DB::table('trans_pembayaran_komisi')->where('id_faktur', $document->id)->first();

        $filePembayaranUrl = null;
        if ($request->hasFile('file_struk_pembayaran')) {
            $dir = public_path('uploads/faktur/komisi_payment');
            File::ensureDirectoryExists($dir);
            $f = $request->file('file_struk_pembayaran');
            $ext = strtolower($f->getClientOriginalExtension() ?: '');
            $name = uniqid('komisi_payment_', true) . ($ext ? '.' . $ext : '');
            $f->move($dir, $name);
            $filePembayaranUrl = 'uploads/faktur/komisi_payment/' . $name;
        }

        $fileKomisiUrl = null;
        if ($request->hasFile('file_struk_komisi')) {
            $dir = public_path('uploads/faktur/komisi_struk');
            File::ensureDirectoryExists($dir);
            $f = $request->file('file_struk_komisi');
            $ext = strtolower($f->getClientOriginalExtension() ?: '');
            $name = uniqid('komisi_struk_', true) . ($ext ? '.' . $ext : '');
            $f->move($dir, $name);
            $fileKomisiUrl = 'uploads/faktur/komisi_struk/' . $name;
        }

        $paid = (float) number_format((float) $data['harga_yang_dibayar'], 2, '.', '');
        $komisi = (float) number_format((float) $data['total_komisi'], 2, '.', '');

        if ($existing) {
            DB::table('trans_pembayaran_komisi')
                ->where('id', $existing->id)
                ->update([
                    'id_mitra' => $data['id_mitra'] ?? $existing->id_mitra,
                    'harga_yang_dibayar' => $paid,
                    'total_komisi' => $komisi,
                    'tanggal' => (string) $data['tanggal'],
                    'file_struk_pembayaran' => $filePembayaranUrl ?? $existing->file_struk_pembayaran,
                    'file_struk_komisi' => $fileKomisiUrl ?? $existing->file_struk_komisi,
                    'updated_at' => now(),
                ]);
            return redirect()->route('admin.master.faktur.index')->with('success', 'Pembayaran komisi diupdate.');
        }

        DB::table('trans_pembayaran_komisi')->insert([
            'id_mitra' => $data['id_mitra'] ?? null,
            'id_faktur' => $document->id,
            'harga_yang_dibayar' => $paid,
            'total_komisi' => $komisi,
            'tanggal' => (string) $data['tanggal'],
            'created_by' => Auth::id(),
            'file_struk_pembayaran' => $filePembayaranUrl,
            'file_struk_komisi' => $fileKomisiUrl,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.master.faktur.index')->with('success', 'Pembayaran komisi disimpan.');
    }
}