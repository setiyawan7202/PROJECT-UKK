<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChecklistTemplate;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistTemplateController extends Controller
{
    public function index()
    {
        $templates = ChecklistTemplate::with(['kategori.barangs.units', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.checklist-templates.index', compact('templates'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('admin.checklist-templates.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.label' => 'required|string|max:100',
        ]);

        // Auto-generate keys
        $itemsWithKeys = array_map(function ($item) {
            return [
                'key' => \Illuminate\Support\Str::slug($item['label'], '_'),
                'label' => $item['label']
            ];
        }, $validated['items']);

        $template = ChecklistTemplate::create([
            'kategori_id' => $validated['kategori_id'],
            'nama' => $validated['nama'],
            'items' => $itemsWithKeys,
            'created_by' => Auth::id(),
        ]);

        \App\Helpers\ActivityLogger::log('Checklist Template', 'Membuat template checklist: ' . $template->nama);

        return redirect()->route('admin.checklist-templates.index')
            ->with('success', 'Template checklist berhasil dibuat.');
    }

    public function edit(ChecklistTemplate $checklistTemplate)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('admin.checklist-templates.edit', compact('checklistTemplate', 'kategoris'));
    }

    public function update(Request $request, ChecklistTemplate $checklistTemplate)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.label' => 'required|string|max:100',
        ]);

        // Auto-generate keys
        $itemsWithKeys = array_map(function ($item) {
            return [
                'key' => \Illuminate\Support\Str::slug($item['label'], '_'),
                'label' => $item['label']
            ];
        }, $validated['items']);

        $checklistTemplate->update([
            'kategori_id' => $validated['kategori_id'],
            'nama' => $validated['nama'],
            'items' => $itemsWithKeys,
        ]);

        \App\Helpers\ActivityLogger::log('Checklist Template', 'Mengupdate template checklist: ' . $checklistTemplate->nama);

        return redirect()->route('admin.checklist-templates.index')
            ->with('success', 'Template checklist berhasil diupdate.');
    }

    public function destroy(ChecklistTemplate $checklistTemplate)
    {
        $nama = $checklistTemplate->nama;
        $checklistTemplate->delete();

        \App\Helpers\ActivityLogger::log('Checklist Template', 'Menghapus template checklist: ' . $nama);

        return redirect()->route('admin.checklist-templates.index')
            ->with('success', 'Template checklist berhasil dihapus.');
    }

    /**
     * Get template by kategori (AJAX)
     */
    public function getByKategori($kategoriId)
    {
        $template = ChecklistTemplate::where('kategori_id', $kategoriId)->first();

        if ($template) {
            return response()->json([
                'success' => true,
                'template' => $template,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada template untuk kategori ini.',
        ]);
    }
}
