@extends('layouts.app')

@section('title', 'Form Penilaian - LAN RI')

@section('content')

    {{-- BREADCRUMB --}}
    <nav class="mb-3 flex items-center gap-2 text-xs text-gray-500 animate-fade-up">
        <a href="{{ route('peserta.penilaian') }}" class="hover:text-blue-600">Peserta & Penilaian</a>
        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-700 font-medium">Penilaian Peserta</span>
    </nav>

    {{-- HEADER --}}
    <div class="mb-6 flex items-center justify-between animate-fade-up">
        <h1 class="text-2xl font-bold text-gray-800">Penilaian Peserta</h1>
        <a href="{{ route('peserta.penilaian') }}"
           class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-600 transition hover:bg-gray-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- BANNER SUDAH FINAL --}}
    @if ($sudahFinal)
        <div class="mb-6 flex items-start gap-3 rounded-xl bg-green-50 border border-green-200 p-4 animate-fade-up">
            <div class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-green-500 text-white">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-green-800">Penilaian sudah diselesaikan</p>
                <p class="text-xs text-green-700 mt-0.5">Anda masih bisa merevisi nilai. Perubahan akan tercatat.</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('penilaian.simpan', $peserta->id) }}" id="formPenilaian">
        @csrf

        {{-- INFO PESERTA --}}
        <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="rounded-xl bg-white p-5 shadow-sm lg:col-span-3">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-slate-100">
                        <svg class="h-9 w-9 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.686-8 6v2h16v-2c0-3.314-3.582-6-8-6z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-gray-800">{{ $peserta->nama }}</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Instansi: <span class="font-medium text-gray-700">{{ $peserta->instansi }}</span>
                        </p>
                        <p class="text-xs text-gray-500">
                            Jabatan: <span class="font-medium text-gray-700">{{ $peserta->jabatan }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL ELEMEN --}}
        <div class="mb-6 rounded-xl bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <div>
                    <h2 class="text-base font-bold text-gray-800">
                        Form Penilaian Ujian {{ ucfirst($tipe) }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Bobot: {{ $tipe === 'wawancara' ? '60%' : '40%' }} dari total nilai
                    </p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold
                    {{ $tipe === 'wawancara' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                    {{ ucfirst($tipe) }}
                </span>
            </div>

            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50 text-left">
                            <th class="px-4 py-3 font-semibold text-gray-600">Judul Unit</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Jenis</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Elemen Kompetensi</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Nilai Anda</th>
                            <th class="px-4 py-3 font-semibold text-gray-600">Catatan Anda</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach ($elemen as $i => $e)
                            @php
                                $tersimpan = $nilaiTersimpan[$i] ?? null;
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    @if ($e[0])<span class="font-semibold text-gray-800">{{ $e[0] }}</span>@endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($e[1])<span class="text-gray-600">{{ $e[1] }}</span>@endif
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $e[2] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number"
                                           name="{{ $tipe }}[{{ $i }}]"
                                           value="{{ $tersimpan?->nilai }}"
                                           class="input-nilai w-20 rounded-lg border border-gray-300 px-2 py-1.5 text-center text-sm font-semibold
                                                  {{ $tipe === 'wawancara' ? 'text-blue-700 focus:border-blue-500 focus:ring-blue-200' : 'text-purple-700 focus:border-purple-500 focus:ring-purple-200' }}
                                                  focus:ring-2 focus:outline-none"
                                           min="0" max="100" step="0.01" placeholder="—">
                                </td>
                                <td class="px-4 py-3">
                                    <textarea rows="1" name="{{ $tipe }}_catatan[{{ $i }}]" placeholder="Catatan..."
                                              class="w-full min-w-[200px] resize-none rounded-lg border border-gray-300 px-2 py-1.5 text-xs
                                                     focus:ring-2 focus:outline-none
                                                     {{ $tipe === 'wawancara' ? 'focus:border-blue-500 focus:ring-blue-200' : 'focus:border-purple-500 focus:ring-purple-200' }}">{{ $tersimpan?->catatan }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="mt-6">
            <div class="mb-4 flex items-start gap-3 rounded-xl bg-blue-50 p-4">
                <div class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-500 text-white">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-700">
                    Passing grade <span class="font-semibold text-blue-600">71.00</span> adalah nilai minimal kelulusan
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div class="rounded-xl bg-white p-5 shadow-sm ring-2 ring-blue-100">
                    <p class="text-xs font-semibold text-gray-500">Rata-rata Nilai Anda</p>
                    <div class="mt-1 flex items-baseline gap-2">
                        <span id="totalRata" class="text-2xl font-bold text-gray-800">0</span>
                        <span class="text-sm text-gray-400">/ 100</span>
                    </div>
                    <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-gray-200">
                        <div id="totalBar" class="h-full rounded-full bg-gradient-to-r from-blue-400 to-blue-600" style="width: 0%"></div>
                    </div>
                </div>

                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-500">Status Kelulusan</p>
                        <span id="statusKelulusan" class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500">
                            Menunggu penilaian
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('peserta.penilaian') }}"
                   class="flex items-center gap-2 rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        onclick="return confirm('Yakin selesaikan penilaian?')"
                        class="flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-600/30 transition hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Selesaikan Penilaian
                </button>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
<script>
    function hitungSemua() {
        const inputNilai = document.querySelectorAll('.input-nilai');
        const totalRata = document.getElementById('totalRata');
        const totalBar = document.getElementById('totalBar');
        const statusKelulusan = document.getElementById('statusKelulusan');

        let total = 0, count = 0;
        inputNilai.forEach(input => {
            if (input.value !== '') {
                total += parseFloat(input.value) || 0;
                count++;
            }
        });

        const rata = count > 0 ? (total / count) : 0;
        totalRata.textContent = rata.toFixed(1).replace('.', ',');
        totalBar.style.width = Math.min(rata, 100) + '%';

        if (count === 0) {
            statusKelulusan.className = 'inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500';
            statusKelulusan.innerHTML = 'Menunggu penilaian';
        } else if (rata >= 71) {
            statusKelulusan.className = 'inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700';
            statusKelulusan.innerHTML = `
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                </svg>
                Memenuhi Passing Grade
            `;
        } else {
            statusKelulusan.className = 'inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700';
            statusKelulusan.innerHTML = `
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                </svg>
                Belum Memenuhi Passing Grade
            `;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.input-nilai').forEach(input => {
            input.addEventListener('input', hitungSemua);
        });
        hitungSemua();
    });
</script>
@endpush