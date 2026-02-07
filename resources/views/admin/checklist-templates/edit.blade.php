@extends('layouts.admin')

@section('title', 'Edit Template Checklist')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.checklist-templates.index') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 mt-2">Edit Template: {{ $checklistTemplate->nama }}</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form action="{{ route('admin.checklist-templates.update', $checklistTemplate) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Template</label>
                    <input type="text" name="nama" value="{{ old('nama', $checklistTemplate->nama) }}" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori_id" required
                            class="searchable-select w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ $checklistTemplate->kategori_id == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Item Checklist</label>
                
                <div id="checklistItems" class="space-y-3">
                    @foreach($checklistTemplate->items ?? [] as $index => $item)
                        <div class="flex gap-3 items-center checklist-item">
                            <input type="text" name="items[{{ $index }}][label]" value="{{ $item['label'] }}" required
                                   class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:border-black focus:ring-1 focus:ring-black outline-none transition">
                            <button type="button" onclick="removeItem(this)" class="text-gray-400 hover:text-red-500 p-2 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
                
                <button type="button" onclick="addItem()" 
                        class="mt-3 text-sm text-gray-900 font-medium hover:text-black hover:underline flex items-center gap-1 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Item
                </button>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.checklist-templates.index') }}" 
                   class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                    Update Template
                </button>
            </div>
        </form>
    </div>

    <script>
        let itemIndex = {{ count($checklistTemplate->items ?? []) }};

        function addItem() {
            const container = document.getElementById('checklistItems');
            const newItem = document.createElement('div');
            newItem.className = 'flex gap-3 items-center checklist-item';
            newItem.innerHTML = `
                <input type="text" name="items[${itemIndex}][label]" placeholder="Item" required
                       class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:border-black focus:ring-1 focus:ring-black outline-none transition">
                <button type="button" onclick="removeItem(this)" class="text-gray-400 hover:text-red-500 p-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            container.appendChild(newItem);
            itemIndex++;
        }

        function removeItem(btn) {
            const items = document.querySelectorAll('.checklist-item');
            if (items.length > 1) {
                btn.closest('.checklist-item').remove();
            }
        }
    </script>
@endsection
