<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransCicilanAkad;
use App\Models\TransCicilanEmas;
use App\Models\MasterAgen;
use App\Models\MasterCustomer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class TransCicilanAkadController extends Controller
{
    public function index()
    {
        $items = TransCicilanAkad::with(['kontrak', 'agen'])
            ->orderByDesc('id')
            ->get();

        return view('admin.trans_cicilan_akad.index', compact('items'));
    }

    public function create()
    {
        $records   = TransCicilanEmas::with(['layanan','agen','gramasi'])->orderByDesc('id')->get();
        $customers = MasterCustomer::where('is_active', true)->orderBy('full_name')->get();
        $agens     = MasterAgen::where('is_active', true)->orderBy('name')->get();
        return view('admin.trans_cicilan_akad.create', [
            'records'   => $records,
            'customers' => $customers,
            'agens'     => $agens,
            'detailed'  => true,
        ]);
    }

    public function createSimple()
    {
        $records = TransCicilanEmas::with(['layanan','agen','gramasi'])->orderByDesc('id')->get();
        return view('admin.trans_cicilan_akad.create', [
            'records'   => $records,
            'customers' => [],
            'agens'     => [],
            'detailed'  => false,
        ]);
    }

    public function storeSimple(Request $request)
    {
        $allowedStatus = ['draft','signed_buyer','signed_seller','signed_both','active','cancelled'];

        $data = $request->validate([
            'trans_cicilan_emas_id' => ['required', 'integer', 'exists:trans_cicilan_emas,id'],
            'nomor_akad'            => ['required', 'string', 'max:50', 'unique:trans_cicilan_akad,nomor_akad'],
            'tanggal_akad'          => ['nullable', 'date'],
            'status'                => ['nullable', Rule::in($allowedStatus)],
            'file_pdf'              => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $record = TransCicilanEmas::findOrFail((int) $data['trans_cicilan_emas_id']);
        $agenId = $record->master_agen_id ? (int) $record->master_agen_id : null;

        $penjualNama   = null;
        $penjualAlamat = null;
        if ($agenId) {
            $agen = MasterAgen::find($agenId);
            $penjualNama   = $agen?->name;
            $penjualAlamat = $agen?->address_line;
        }

        $attrs = [
            'trans_cicilan_emas_id' => (int) $data['trans_cicilan_emas_id'],
            'master_agen_id'        => $agenId,
            'nomor_akad'            => $data['nomor_akad'],
            'tanggal_akad'          => $data['tanggal_akad'] ?? null,
            'akad_type'             => 'murabahah',
            'pihak_penjual_type'    => 'agen',
            'penjual_nama'          => $penjualNama,
            'penjual_alamat'        => $penjualAlamat,
            'gramasi_total'         => (float) $record->total_gram_dibuka,
            'status'                => $data['status'] ?? 'draft',
        ];

        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            if (!$file->isValid()) {
                return back()->withErrors(['file_pdf' => 'File PDF tidak valid atau gagal diupload.'])->withInput();
            }
            $dir = public_path('uploads/akad/pdf');
            File::ensureDirectoryExists($dir);
            $ext = strtolower($file->getClientOriginalExtension() ?: 'pdf');
            $filename = 'akad_' . uniqid('', true) . '.' . $ext;
            $file->move($dir, $filename);
            $attrs['file_pdf_url'] = 'uploads/akad/pdf/' . $filename;
        }

        TransCicilanAkad::create($attrs);

        return redirect()->route('admin.trans.cicilan-akad.index')->with('success', 'Akad murabahah (sederhana) berhasil dibuat.');
    }

    public function store(Request $request)
    {
        $allowedStatus = ['draft','signed_buyer','signed_seller','signed_both','active','cancelled'];

        $data = $request->validate([
            'trans_cicilan_emas_id' => ['required', 'integer', 'exists:trans_cicilan_emas,id'],
            'master_agen_id'        => ['required', 'integer', 'exists:master_agen,id'],
            'nomor_akad'            => ['required', 'string', 'max:50', 'unique:trans_cicilan_akad,nomor_akad'],
            'tanggal_akad'          => ['nullable', 'date'],
            'status'                => ['nullable', Rule::in($allowedStatus)],
            'file_pdf'              => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'gramasi_total'         => ['nullable', 'numeric', 'min:0'],
            'harga_per_gram_fix'    => ['nullable', 'numeric', 'min:0'],
            'harga_total_kontrak'   => ['nullable', 'numeric', 'min:0'],
            'tenor_bulan'           => ['nullable', 'integer', 'min:1'],
            'dp_amount'             => ['nullable', 'numeric', 'min:0'],
            'cicilan_per_bulan'     => ['nullable', 'numeric', 'min:0'],
            'margin_persen'         => ['nullable', 'numeric', 'min:0'],
            'margin_amount_total'   => ['nullable', 'numeric', 'min:0'],
            'buyer_signed_at'       => ['nullable', 'date'],
            'seller_signed_at'      => ['nullable', 'date'],
            'buyer_signature_url'   => ['nullable', 'string', 'max:255'],
            'seller_signature_url'  => ['nullable', 'string', 'max:255'],
            'syarat_ketentuan'      => ['nullable', 'string'],
            'catatan'               => ['nullable', 'string'],
        ]);

        $record = TransCicilanEmas::findOrFail((int) $data['trans_cicilan_emas_id']);

        $agenId     = (int) $data['master_agen_id'];

        $penjualNama   = null;
        $penjualAlamat = null;
        if ($agenId) {
            $agen = MasterAgen::find($agenId);
            $penjualNama   = $agen?->name;
            $penjualAlamat = $agen?->address_line;
        }

        $attrs = array_merge($data, [
            'master_agen_id'       => $agenId,
            'akad_type'            => 'murabahah',
            'pihak_penjual_type'   => 'agen',
            'penjual_nama'         => $penjualNama,
            'penjual_alamat'       => $penjualAlamat,
            'gramasi_total'        => isset($data['gramasi_total']) ? (float)$data['gramasi_total'] : (float) $record->total_gram_dibuka,
            'harga_per_gram_fix'   => $data['harga_per_gram_fix'] ?? null,
            'harga_total_kontrak'  => $data['harga_total_kontrak'] ?? null,
            'tenor_bulan'          => $data['tenor_bulan'] ?? null,
            'dp_amount'            => $data['dp_amount'] ?? null,
            'cicilan_per_bulan'    => $data['cicilan_per_bulan'] ?? null,
            'margin_persen'        => $data['margin_persen'] ?? null,
            'margin_amount_total'  => $data['margin_amount_total'] ?? null,
            'buyer_signed_at'      => $data['buyer_signed_at'] ?? null,
            'seller_signed_at'     => $data['seller_signed_at'] ?? null,
            'buyer_signature_url'  => $data['buyer_signature_url'] ?? null,
            'seller_signature_url' => $data['seller_signature_url'] ?? null,
            'status'               => $data['status'] ?? 'draft',
        ]);

        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            if (!$file->isValid()) {
                return back()->withErrors(['file_pdf' => 'File PDF tidak valid atau gagal diupload.'])->withInput();
            }
            $dir = public_path('uploads/akad/pdf');
            File::ensureDirectoryExists($dir);
            $ext = strtolower($file->getClientOriginalExtension() ?: 'pdf');
            $filename = 'akad_' . uniqid('', true) . '.' . $ext;
            $file->move($dir, $filename);
            $attrs['file_pdf_url'] = 'uploads/akad/pdf/' . $filename;
        }

        unset($attrs['file_pdf']);

        TransCicilanAkad::create($attrs);

        return redirect()
            ->route('admin.trans.cicilan-akad.index')
            ->with('success', 'Akad murabahah berhasil dibuat.');
    }

    public function edit(TransCicilanAkad $akad)
    {
        $records   = TransCicilanEmas::with(['layanan','agen','gramasi'])->orderByDesc('id')->get();
        $customers = MasterCustomer::where('is_active', true)->orderBy('full_name')->get();
        $agens     = MasterAgen::where('is_active', true)->orderBy('name')->get();
        return view('admin.trans_cicilan_akad.edit', [
            'akad'      => $akad,
            'records'   => $records,
            'customers' => $customers,
            'agens'     => $agens,
            'detailed'  => true,
        ]);
    }

    public function update(Request $request, TransCicilanAkad $akad)
    {
        $allowedStatus = ['draft','signed_buyer','signed_seller','signed_both','active','cancelled'];

        $data = $request->validate([
            'trans_cicilan_emas_id' => ['required', 'integer', 'exists:trans_cicilan_emas,id'],
            'master_agen_id'        => ['required', 'integer', 'exists:master_agen,id'],
            'nomor_akad'            => ['required', 'string', 'max:50', Rule::unique('trans_cicilan_akad', 'nomor_akad')->ignore($akad->id)],
            'tanggal_akad'          => ['nullable', 'date'],
            'status'                => ['nullable', Rule::in($allowedStatus)],
            'file_pdf'              => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'gramasi_total'         => ['nullable', 'numeric', 'min:0'],
            'harga_per_gram_fix'    => ['nullable', 'numeric', 'min:0'],
            'harga_total_kontrak'   => ['nullable', 'numeric', 'min:0'],
            'tenor_bulan'           => ['nullable', 'integer', 'min:1'],
            'dp_amount'             => ['nullable', 'numeric', 'min:0'],
            'cicilan_per_bulan'     => ['nullable', 'numeric', 'min:0'],
            'margin_persen'         => ['nullable', 'numeric', 'min:0'],
            'margin_amount_total'   => ['nullable', 'numeric', 'min:0'],
            'buyer_signed_at'       => ['nullable', 'date'],
            'seller_signed_at'      => ['nullable', 'date'],
            'buyer_signature_url'   => ['nullable', 'string', 'max:255'],
            'seller_signature_url'  => ['nullable', 'string', 'max:255'],
            'syarat_ketentuan'      => ['nullable', 'string'],
            'catatan'               => ['nullable', 'string'],
        ]);

        $record = TransCicilanEmas::findOrFail((int) $data['trans_cicilan_emas_id']);

        $agenId     = (int) $data['master_agen_id'];

        $penjualNama   = null;
        $penjualAlamat = null;
        if ($agenId) {
            $agen = MasterAgen::find($agenId);
            $penjualNama   = $agen?->name;
            $penjualAlamat = $agen?->address_line;
        }

        $attrs = array_merge($data, [
            'master_agen_id'       => $agenId,
            'akad_type'            => 'murabahah',
            'pihak_penjual_type'   => 'agen',
            'penjual_nama'         => $penjualNama,
            'penjual_alamat'       => $penjualAlamat,
            'gramasi_total'        => isset($data['gramasi_total']) ? (float)$data['gramasi_total'] : (float) $record->total_gram_dibuka,
            'harga_per_gram_fix'   => $data['harga_per_gram_fix'] ?? null,
            'harga_total_kontrak'  => $data['harga_total_kontrak'] ?? null,
            'tenor_bulan'          => $data['tenor_bulan'] ?? null,
            'dp_amount'            => $data['dp_amount'] ?? null,
            'cicilan_per_bulan'    => $data['cicilan_per_bulan'] ?? null,
            'margin_persen'        => $data['margin_persen'] ?? null,
            'margin_amount_total'  => $data['margin_amount_total'] ?? null,
            'buyer_signed_at'      => $data['buyer_signed_at'] ?? null,
            'seller_signed_at'     => $data['seller_signed_at'] ?? null,
            'buyer_signature_url'  => $data['buyer_signature_url'] ?? null,
            'seller_signature_url' => $data['seller_signature_url'] ?? null,
        ]);

        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            if (!$file->isValid()) {
                return back()->withErrors(['file_pdf' => 'File PDF tidak valid atau gagal diupload.'])->withInput();
            }
            $dir = public_path('uploads/akad/pdf');
            File::ensureDirectoryExists($dir);
            $ext = strtolower($file->getClientOriginalExtension() ?: 'pdf');
            $filename = 'akad_' . uniqid('', true) . '.' . $ext;
            $file->move($dir, $filename);
            $attrs['file_pdf_url'] = 'uploads/akad/pdf/' . $filename;
        }

        unset($attrs['file_pdf']);

        $akad->update($attrs);

        return redirect()
            ->route('admin.trans.cicilan-akad.index')
            ->with('success', 'Akad murabahah berhasil diupdate.');
    }

    public function show(TransCicilanAkad $akad)
    {
        $akad->load(['kontrak', 'agen']);
        return view('admin.trans_cicilan_akad.show', compact('akad'));
    }

    public function destroy(TransCicilanAkad $akad)
    {
        $akad->delete();

        return redirect()
            ->route('admin.trans.cicilan-akad.index')
            ->with('success', 'Akad murabahah berhasil dihapus.');
    }
}