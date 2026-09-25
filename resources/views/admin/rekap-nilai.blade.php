@extends('layouts.app')

@section('title', 'Rekap Nilai - LAN RI')

@section('content')
    <div class="mb-6 animate-fade-up flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Rekap Nilai UKom</h1>
            <p class="mt-1 text-sm text-gray-500">Rekapitulasi nilai semua peserta</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.export.csv') }}"
               class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                Export CSV
            </a>
            <a href="{{ route('admin.export.pdf') }}"
               class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                Export PDF
            </a>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50 text-left">
                    <th class="px-4 py-3 font-semibold text-gray-600">No</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Nama</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Instansi</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Rata Wawancara</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Rata Tertulis</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Nilai Akhir</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesertas as $i => $p)
                    @php
                        $w = $p->rataWawancara();
                        $t = $p->rataTertulis();
                        $akhir = $p->nilaiAkhir();
                        $status = $p->statusKelulusan();
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $p->nama }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->instansi }}</td>
                        <td class="px-4 py-3 text-center">{{ $w !== null ? number_format($w, 2) : '—' }}</td>
                        <td class="px-4 py-3 text-center">{{ $t !== null ? number_format($t, 2) : '—' }}</td>
                        <td class="px-4 py-3 text-center font-bold text-gray-800">
                            {{ $akhir !== null ? number_format($akhir, 2) : '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($status === 'lulus')
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">LULUS</span>
                            @elseif ($status === 'tidak_lulus')
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">TIDAK LULUS</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500">BELUM DINILAI</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection