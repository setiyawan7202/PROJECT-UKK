@extends('layouts.admin')

@section('title', 'Catat Aktivitas Maintenance')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.maintenance.logs') }}"
            class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 mt-2">Catat Aktivitas Maintenance</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form action="{{ route('admin.maintenance.logs.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Barang</label>
                    <select name="barang_unit_id" required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                        <option value="">Pilih Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">
                                {{ $unit->kode_unit }} - {{ $unit->barang->nama_barang ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jadwal Terkait (Opsional)</label>
                    <select name="schedule_id"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                        <option value="">Tidak Terkait Jadwal</option>
                        @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}">
                                {{ $schedule->kategori->nama ?? $schedule->barang->nama_barang ?? '-' }}
                                ({{ $schedule->interval_days }} hari)
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Jika terkait jadwal, tanggal maintenance berikutnya akan otomatis
                        diperbarui</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Maintenance</label>
                    <input type="date" name="maintenance_date" value="{{ old('maintenance_date', now()->format('Y-m-d')) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Teknisi</label>
                    <input type="text" name="technician_name" value="{{ old('technician_name') }}" required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                        placeholder="Nama teknisi yang melakukan maintenance">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Pekerjaan</label>
                <textarea name="description" rows="3" required
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                    placeholder="Apa yang dilakukan? (pembersihan, penggantian spare part, kalibrasi, dsb.)">{{ old('description') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="notes" rows="2"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                    placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.maintenance.logs') }}"
                    class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection