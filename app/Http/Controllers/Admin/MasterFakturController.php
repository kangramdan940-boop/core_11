<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterFaktur;
use App\Models\MasterFakturItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class MasterFakturController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->get('q', '');
        $documents = MasterFaktur::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('invoice_number', 'like', "%{$q}%")
                      ->orWhere('customer_name', 'like', "%{$q}%")
                      ->orWhere('payment_no', 'like', "%{$q}%");
            })
            ->withCount('items')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.master_faktur.index', compact('documents', 'q'));
    }

    public function show(MasterFaktur $document)
    {
        $document->load('items', 'stock');
        return view('admin.master_faktur.show', compact('document'));
    }

    public function create()
    {
        $document = new MasterFaktur();
        $document->setRelation('items', collect());
        return view('admin.master_faktur.create', compact('document'));
    }

    public function store(Request $request)
    {
        $data = $this->validateDocument($request);
        $items = (array) ($data['items'] ?? []);
        unset($data['items']);

        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            if (!$file->isValid()) {
                return back()->withErrors(['pdf_file' => 'File PDF tidak valid atau gagal diupload.'])->withInput();
            }
            $dir = public_path('uploads/fakturs/pdf');
            File::ensureDirectoryExists($dir);
            $ext = strtolower($file->getClientOriginalExtension() ?: 'pdf');
            $filename = uniqid('faktur_', true) . '.' . $ext;
            $file->move($dir, $filename);
            $data['pdf_url'] = 'uploads/fakturs/pdf/' . $filename;
        }



        $document = DB::transaction(function () use ($data, $items) {
            $doc = MasterFaktur::create($data);
            foreach ($items as $it) {
                MasterFakturItem::create([
                    'document_id' => $doc->id,
                    'no' => (int) ($it['no'] ?? 0),
                    'description' => (string) ($it['description'] ?? ''),
                    'quantity_pcs' => (int) ($it['quantity_pcs'] ?? 0),
                    'weight_kg' => (float) ($it['weight_kg'] ?? 0),
                    'unit_price_idr' => (int) ($it['unit_price_idr'] ?? 0),
                    'total_idr' => (int) ($it['total_idr'] ?? 0),
                ]);
            }
            return $doc;
        });

        return redirect()->route('admin.master.faktur.index')->with('success', 'Faktur berhasil dibuat.');
    }

    public function edit(MasterFaktur $document)
    {
        $document->load('items');
        return view('admin.master_faktur.edit', compact('document'));
    }

    public function update(Request $request, MasterFaktur $document)
    {
        $data = $this->validateDocument($request);
        $items = (array) ($data['items'] ?? []);
        unset($data['items']);

        DB::transaction(function () use ($document, $data, $items) {
            $document->fill($data);
            $document->save();

            MasterFakturItem::where('document_id', $document->id)->delete();
            foreach ($items as $it) {
                MasterFakturItem::create([
                    'document_id' => $document->id,
                    'no' => (int) ($it['no'] ?? 0),
                    'description' => (string) ($it['description'] ?? ''),
                    'quantity_pcs' => (int) ($it['quantity_pcs'] ?? 0),
                    'weight_kg' => (float) ($it['weight_kg'] ?? 0),
                    'unit_price_idr' => (int) ($it['unit_price_idr'] ?? 0),
                    'total_idr' => (int) ($it['total_idr'] ?? 0),
                ]);
            }
        });

        return redirect()->route('admin.master.faktur.index')->with('success', 'Faktur berhasil diupdate.');
    }

    private function validateDocument(Request $request): array
    {
        $validated = $request->validate([
            'master_gold_stock_id' => ['nullable','integer','exists:master_gold_stock,id'],
            'issuer_company' => ['nullable','string'],
            'issuer_business_unit' => ['nullable','string'],
            'issuer_address' => ['nullable','string'],
            'issuer_website' => ['nullable','string','max:255'],
            'issuer_phone' => ['nullable','string','max:50'],
            'issuer_npwp' => ['nullable','string','max:50'],
            'issuer_npwp_holder' => ['nullable','string'],
            'issuer_npwp_address' => ['nullable','string'],
            'authorized_receiver_name' => ['nullable','string'],
            'authorized_receiver_nik' => ['nullable','string','max:20'],
            'invoice_number' => ['nullable','string','max:100', Rule::unique('gold_stock_documents','invoice_number')->ignore(optional($request->route('document'))->id)],
            'reference' => ['nullable','string','max:100'],
            'transaction_type' => ['nullable','string','max:100'],
            'date_raw' => ['nullable','string','max:100'],
            'date' => ['nullable','date'],
            'customer_name' => ['nullable','string'],
            'membership_number' => ['nullable','string','max:50'],
            'membership_tier' => ['nullable','string','max:50'],
            'service_name' => ['nullable','string','max:100'],
            'boutique_code_name' => ['nullable','string','max:100'],
            'boutique_location' => ['nullable','string'],
            'grand_total_idr' => ['nullable','integer','min:0'],
            'dpp_idr' => ['nullable','integer','min:0'],
            'ppn_rate' => ['nullable','integer','min:0','max:100'],
            'ppn_idr' => ['nullable','integer','min:0'],
            'currency' => ['nullable','string','max:3'],
            'payment_method' => ['nullable','string','max:100'],
            'virtual_account' => ['nullable','string','max:50'],
            'payment_no' => ['nullable','string','max:100'],
            'created_by' => ['nullable','string','max:255'],
            'print_by' => ['nullable','string','max:255'],
            'pdf_file' => ['nullable','file','mimes:pdf','max:51200'],
            'raw_text' => ['nullable','string'],
            'notes' => ['nullable','string'],
            'items' => ['nullable','array'],
            'items.*.no' => ['required','integer','min:1'],
            'items.*.description' => ['required','string','max:255'],
            'items.*.quantity_pcs' => ['required','integer','min:0'],
            'items.*.weight_kg' => ['required','numeric','min:0'],
            'items.*.unit_price_idr' => ['required','integer','min:0'],
            'items.*.total_idr' => ['required','integer','min:0'],
        ]);

        $notesStr = (string) ($validated['notes'] ?? '');
        $validated['notes'] = $notesStr !== '' ? (json_decode($notesStr, true) ?: null) : null;

        return $validated;
    }
}
