@extends('layouts.admin')

@section('title', 'Analisis Aset & Lokasi')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Analisis Aset & Lokasi</h1>
                <p class="text-gray-500">Analisis risiko aset, rekomendasi lifecycle, dan sebaran lokasi</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    Cetak Laporan
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Summary Cards -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-gray-500 text-sm font-medium mb-2">Total Aset Risiko Tinggi</h3>
            <p class="text-3xl font-bold text-red-600">{{ collect($risks)->where('recommendation', 'REPLACE')->count() }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Rekomendasi: Replace</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-gray-500 text-sm font-medium mb-2">Perlu Perbaikan</h3>
            <p class="text-3xl font-bold text-yellow-500">{{ collect($risks)->where('recommendation', 'REPAIR')->count() }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Rekomendasi: Repair</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-gray-500 text-sm font-medium mb-2">Total Lokasi</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $locations->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">Ruangan/Gedung Terdata</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Risk Analysis Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Analisis Risiko & Lifecycle (Top 20)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">Aset</th>
                            <th class="px-6 py-3">Faktor Risiko</th>
                            <th class="px-6 py-3">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($risks as $risk)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $risk['unit']->barang->nama_barang ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $risk['unit']->kode_unit }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $risk['factors'] }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($risk['color'] == 'red') bg-red-100 text-red-800
                                        @elseif($risk['color'] == 'yellow') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $risk['recommendation'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">Tidak ada aset berisiko tinggi saat
                                    ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Location Stats Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden h-fit">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Sebaran Aset per Lokasi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">Lokasi (Ruangan)</th>
                            <th class="px-6 py-3 text-right">Total Aset</th>
                            <th class="px-6 py-3">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $totalAll = $locations->sum('total'); @endphp
                        @forelse($locations as $loc)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $loc->lokasi }}
                                </td>
                                <td class="px-6 py-4 text-right text-gray-600">
                                    {{ $loc->total }} Unit
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                                        <div class="bg-blue-600 h-1.5 rounded-full"
                                            style="width: {{ $totalAll > 0 ? ($loc->total / $totalAll) * 100 : 0 }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada data lokasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection