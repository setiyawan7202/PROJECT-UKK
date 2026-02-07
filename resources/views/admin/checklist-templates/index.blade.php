@extends('layouts.admin')

@section('title', 'Template Checklist')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Template Checklist</h1>
            <p class="text-sm text-gray-500">Kelola template checklist inspeksi per kategori</p>
        </div>
        <a href="{{ route('admin.checklist-templates.create') }}"
            class="inline-flex items-center px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Template
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <th class="px-4 py-3 font-medium">Nama Template</th>
                        <th class="px-4 py-3 font-medium">Kategori</th>
                        <th class="px-4 py-3 font-medium">Total Unit</th>
                        <th class="px-4 py-3 font-medium">Poin Inspeksi</th>
                        <th class="px-4 py-3 font-medium">Pembuat</th>
                        <th class="px-4 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($templates as $template)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $template->nama }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $template->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                                    {{ $template->kategori ? $template->kategori->barangs->sum(fn($b) => $b->units->count()) : 0 }}
                                    unit
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                                    {{ count($template->items ?? []) }} poin
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $template->creator->nama_lengkap ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.checklist-templates.edit', $template) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.checklist-templates.destroy', $template) }}" method="POST"
                                        onsubmit="return confirm('Hapus template ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Belum ada template checklist.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $templates->links() }}
    </div>
@endsection