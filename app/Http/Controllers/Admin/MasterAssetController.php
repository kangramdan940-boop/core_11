<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MasterAssetController extends Controller
{
    public function index()
    {
        $assets = MasterAsset::orderByDesc('id')->get();
        return view('admin.master_asset.index', compact('assets'));
    }

    public function create()
    {
        return view('admin.master_asset.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['nullable', 'string', 'max:150'],
            'type'        => ['nullable', 'string', 'max:50'],
            'category'    => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:active,inactive'],
            'url'         => ['nullable', 'string', 'max:255'],
            'file'        => ['required', 'file', 'max:51200'],
        ]);

        $file = $request->file('file');
        $hash = hash_file('sha256', $file->getRealPath());

        $exists = MasterAsset::where('file_hash', $hash)->first();
        if ($exists) {
            return redirect()
                ->route('admin.master.assets.index')
                ->with('success', 'Aset dengan file yang sama sudah ada. Menggunakan data yang sudah tersimpan.');
        }

        $dir = public_path('uploads/assets');
        File::ensureDirectoryExists($dir);

        $ext = strtolower($file->getClientOriginalExtension() ?: '');
        $filename = $hash . ($ext ? '.' . $ext : '');
        $fullPath = $dir . '/' . $filename;

        $size = $file->getSize();
        if (!File::exists($fullPath)) {
            $file->move($dir, $filename);
            if (!$size) {
                try { $size = File::size($fullPath); } catch (\Throwable $e) { $size = null; }
            }
        }

        $payload = [
            'title'          => $data['title'] ?? null,
            'type'           => $data['type'] ?? null,
            'category'       => $data['category'] ?? null,
            'url'            => 'uploads/assets/' . $filename,
            'file_hash'      => $hash,
            'file_size'      => $size,
            'file_extension' => $ext,
            'description'    => $data['description'] ?? null,
            'status'         => $data['status'],
            'created_by'     => auth()->id(),
        ];

        MasterAsset::create($payload);

        return redirect()
            ->route('admin.master.assets.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit(MasterAsset $asset)
    {
        return view('admin.master_asset.edit', compact('asset'));
    }

    public function update(Request $request, MasterAsset $asset)
    {
        $data = $request->validate([
            'title'       => ['nullable', 'string', 'max:150'],
            'type'        => ['nullable', 'string', 'max:50'],
            'category'    => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:active,inactive'],
            'url'         => ['nullable', 'string', 'max:255'],
            'file'        => ['nullable', 'file', 'max:51200'],
        ]);

        $payload = [
            'title'       => $data['title'] ?? null,
            'type'        => $data['type'] ?? null,
            'category'    => $data['category'] ?? null,
            'description' => $data['description'] ?? null,
            'status'      => $data['status'],
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $newHash = hash_file('sha256', $file->getRealPath());
            $dup = MasterAsset::where('file_hash', $newHash)->where('id', '!=', $asset->id)->exists();
            if ($dup) {
                return back()->withErrors(['file' => 'File yang sama sudah tersimpan sebagai aset lain.'])->withInput();
            }

            $dir = public_path('uploads/assets');
            File::ensureDirectoryExists($dir);
            $ext = strtolower($file->getClientOriginalExtension() ?: '');
            $filename = $newHash . ($ext ? '.' . $ext : '');
            $fullPath = $dir . '/' . $filename;

            $size = $file->getSize();
            if (!File::exists($fullPath)) {
                $file->move($dir, $filename);
                if (!$size) {
                    try { $size = File::size($fullPath); } catch (\Throwable $e) { $size = null; }
                }
            }

            $payload['url']            = 'uploads/assets/' . $filename;
            $payload['file_hash']      = $newHash;
            $payload['file_size']      = $size;
            $payload['file_extension'] = $ext;
        } else {
            if (!empty($data['url'])) {
                $payload['url'] = $data['url'];
            }
        }

        $asset->update($payload);

        return redirect()
            ->route('admin.master.assets.index')
            ->with('success', 'Aset berhasil diupdate.');
    }

    public function destroy(MasterAsset $asset)
    {
        $asset->delete();

        return redirect()
            ->route('admin.master.assets.index')
            ->with('success', 'Aset berhasil dihapus.');
    }
}