@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Sampah Kelas</h1>
            <p class="text-gray-600">Daftar kelas yang telah dihapus sementara.</p>
        </div>
        <a href="{{ route('admin.kelas.index') }}"
            class="px-4 py-2 bg-gray-500 text-white rounded-lg text-sm font-medium hover:bg-gray-600 transition">
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-xl lg:rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jurusan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tingkat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Dihapus Pada</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kelasList as $kelas)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $kelas->nama_kelas }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $kelas->jurusan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $kelas->tingkat }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $kelas->deleted_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <form action="{{ route('admin.kelas.restore', $kelas->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-xs font-medium">
                                        Pulihkan
                                    </button>
                                </form>
                                <form action="{{ route('admin.kelas.force_delete', $kelas->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus permanen? Data tidak bisa dikembalikan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-xs font-medium">
                                        Hapus Permanen
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data sampah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection