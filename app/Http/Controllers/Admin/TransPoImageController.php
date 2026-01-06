<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransPo;
use App\Models\TransPoImage;
use App\Models\TransPoLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class TransPoImageController extends Controller
{
    public function store(Request $request, TransPo $po): RedirectResponse
    {
        $request->validate([
            'title'      => ['nullable', 'string', 'max:100'],
            'gold_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if (TransPoImage::where('trans_po_id', $po->id)->exists()) {
            return back()->withErrors(['gold_image' => 'Gambar emas untuk PO ini sudah ada.'])->withInput();
        }

        $file = $request->file('gold_image');
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = 'po-' . $po->id . '-' . date('YmdHis') . '.' . $ext;
        $path = $file->storeAs('po_images', $filename, 'public');

        TransPoImage::create([
            'trans_po_id' => $po->id,
            'file_path'   => $path,
            'mime_type'   => $file->getMimeType() ?? 'image/' . $ext,
            'title'       => trim((string) $request->input('title')) ?: null,
            'size_bytes'  => (int) $file->getSize(),
            'uploaded_by' => (int) ($request->user()?->id ?? 0),
        ]);

        TransPoLog::create([
            'trans_po_id' => $po->id,
            'status'      => $po->status,
            'description' => 'Upload gambar emas oleh ' . ($request->user()?->name ?? 'SYSTEM'),
        ]);

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Gambar emas berhasil diupload.');
    }

    public function update(Request $request, TransPo $po, TransPoImage $image): RedirectResponse
    {
        if ((int) $image->trans_po_id !== (int) $po->id) {
            abort(404);
        }

        $request->validate([
            'title'      => ['nullable', 'string', 'max:100'],
            'gold_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $title = trim((string) $request->input('title')) ?: null;

        if ($request->hasFile('gold_image')) {
            $file = $request->file('gold_image');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = 'po-' . $po->id . '-' . date('YmdHis') . '.' . $ext;
            $path = $file->storeAs('po_images', $filename, 'public');

            if ($image->file_path) {
                Storage::disk('public')->delete($image->file_path);
            }

            $image->file_path = $path;
            $image->mime_type = $file->getMimeType() ?? 'image/' . $ext;
            $image->size_bytes = (int) $file->getSize();
        }

        $image->title = $title;
        $image->uploaded_by = (int) ($request->user()?->id ?? 0);
        $image->save();

        TransPoLog::create([
            'trans_po_id' => $po->id,
            'status'      => $po->status,
            'description' => 'Edit gambar emas oleh ' . ($request->user()?->name ?? 'SYSTEM'),
        ]);

        return redirect()->route('admin.trans.po.show', $po)->with('success', 'Gambar emas berhasil diperbarui.');
    }
}