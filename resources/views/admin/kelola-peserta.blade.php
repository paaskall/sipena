@extends('layouts.app')

@section('title', 'Kelola Peserta - LAN RI')

@section('content')
    <div class="mb-6 animate-fade-up flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Peserta</h1>
            <p class="mt-1 text-sm text-gray-500">Setiap peserta wajib memiliki 2 penguji</p>
        </div>
        <a href="{{ route('admin.kelola-peserta.create') }}"
           class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            + Tambah Peserta
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl bg-white shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50 text-left">
                    <th class="px-4 py-3 font-semibold text-gray-600">No</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Nama</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Instansi / Jabatan</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Penguji</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Tipe Ujian</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesertas as $i => $p)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $p->nama }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $p->instansi }}<br>
                            <span class="text-xs text-gray-400">{{ $p->jabatan }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @foreach ($p->pengujis as $pj)
                                <div class="text-xs">
                                    <span class="font-semibold text-gray-700">{{ $pj->nama }}</span>
                                    <span class="text-gray-400">({{ str_replace('_', ' ', $pj->pivot->peran) }})</span>
                                </div>
                            @endforeach
                        </td>
                        <td class="px-4 py-3">
                            @if ($p->butuh_tertulis)
                                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
                                    Wawancara + Tertulis
                                </span>
                            @else
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                    2 Wawancara
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.kelola-peserta.edit', $p->id) }}"
                                   class="rounded-lg border border-blue-500 px-3 py-1 text-xs font-semibold text-blue-600 hover:bg-blue-50">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.kelola-peserta.destroy', $p->id) }}"
                                      onsubmit="return confirm('Hapus peserta ini?')">
                                    @csrf @method('DELETE')
                                    <button class="rounded-lg border border-red-500 px-3 py-1 text-xs font-semibold text-red-600 hover:bg-red-50">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada peserta</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection