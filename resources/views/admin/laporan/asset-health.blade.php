@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kesehatan Aset (Asset Health)</h1>
                <p class="text-gray-500">Dashboard monitoring kondisi dan kesehatan aset sarana prasarana</p>
            </div>
        </div>
    </div>

    {{-- Summary Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        {{-- Total Assets --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Asset Units</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalAssets) }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-50 border border-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Active Assets --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Aset Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($activeAssets) }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $totalAssets > 0 ? number_format(($activeAssets / $totalAssets) * 100, 1) : 0 }}% dari total
                    </p>
                </div>
                <div class="w-12 h-12 bg-white border border-gray-200 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Damaged Assets --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Aset Rusak</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($damagedCount) }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $totalAssets > 0 ? number_format(($damagedCount / $totalAssets) * 100, 1) : 0 }}% dari total
                    </p>
                </div>
                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Lost Assets --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Aset Hilang</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($lostCount) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total kejadian</p>
                </div>
                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Asset by Condition Chart --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Distribusi Kondisi Aset</h3>
            <canvas id="conditionChart" class="w-full" style="max-height: 300px;"></canvas>
        </div>

        {{-- Top 10 Most Damaged Assets --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                </svg>
                Top 10 Aset Paling Sering Rusak
            </h3>
            <div class="space-y-3">
                @forelse($topDamagedAssets as $index => $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div
                            class="flex-shrink-0 w-8 h-8 rounded-full {{ $index < 3 ? 'bg-black text-white' : 'bg-gray-200 text-gray-700' }} flex items-center justify-center font-bold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $item->nama_barang }}</p>
                            <p class="text-xs text-gray-500">{{ $item->kode }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span
                                class="px-3 py-1 bg-gray-100 text-gray-800 border border-gray-200 rounded-full text-xs font-bold">
                                {{ $item->total_rusak }}x rusak
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm">Belum ada data kerusakan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Damage Trend Chart --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            Trend Kerusakan (6 Bulan Terakhir)
        </h3>
        <div class="relative h-64">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    {{-- Daftar Alat Rusak --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Daftar Unit Aset Rusak ({{ $damagedItems->count() }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Unit
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($damagedItems as $unit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-medium text-gray-900">{{ $unit->kode_unit }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $unit->barang->nama_barang ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $unit->barang->kategori->nama_kategori ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-300">
                                    {{ ucfirst($unit->kondisi) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-black text-white">
                                    Rusak
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <p class="text-sm">Tidak ada unit yang rusak</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Daftar Alat Hilang --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Daftar Aset Hilang ({{ $lostItems->count() }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Unit
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lostItems as $unit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-medium text-gray-900">{{ $unit->kode_unit }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $unit->barang->nama_barang ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $unit->barang->kategori->nama_kategori ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">
                                    Hilang
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm">Tidak ada aset yang hilang</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Damage History --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Riwayat Kerusakan Terbaru (30 Hari Terakhir)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentDamages as $damage)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($damage->created_at)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $damage->nama_barang }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $damage->nama_lengkap }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $kondisiColors = [
                                        'rusak_ringan' => 'bg-white border border-gray-300 text-gray-700',
                                        'rusak_berat' => 'bg-black text-white',
                                        'hilang' => 'bg-gray-200 text-gray-800',
                                    ];
                                @endphp
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $kondisiColors[$damage->kondisi] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ str_replace('_', ' ', ucfirst($damage->kondisi)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $damage->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm">Tidak ada riwayat kerusakan dalam 30 hari terakhir</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('conditionChart').getContext('2d');
        const conditionData = @json($assetsByCondition);

        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(conditionData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
                datasets: [{
                    data: Object.values(conditionData),
                    backgroundColor: [
                        '#10b981', // green - baik
                        '#f97316', // orange - rusak ringan
                        '#ef4444', // red - rusak berat
                        '#6b7280', // gray - hilang (neutral/dark)
                        '#9ca3af', // gray-400 - lainnya
                    ],
                    backgroundColor: [
                        '#10b981', // green - baik
                        '#f97316', // orange - rusak ringan
                        '#ef4444', // red - rusak berat
                        '#6b7280', // gray - hilang (neutral/dark)
                        '#9ca3af', // gray-400 - lainnya
                    ],
                    borderWidth: 1,
                    borderColor: '#000000'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} unit (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Trend Line Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendData = @json($damageTrend);

        // Generate last 6 months labels
        const months = [];
        const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        for (let i = 5; i >= 0; i--) {
            const d = new Date();
            d.setMonth(d.getMonth() - i);
            months.push(d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0'));
        }

        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: months.map(m => {
                    const [y, mo] = m.split('-');
                    return monthLabels[parseInt(mo) - 1] + ' ' + y;
                }),
                datasets: [{
                    label: 'Jumlah Kerusakan',
                    data: months.map(m => trendData[m] || 0),
                    borderColor: '#111827',
                    backgroundColor: 'rgba(17, 24, 39, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#111827',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#111827',
                        bodyColor: '#4b5563',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 10
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 2],
                            color: '#f3f4f6'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection