@extends('layouts.app')

@section('title', 'Peserta & Penilaian - LAN RI')

@section('content')

    {{-- ================= DAFTAR PESERTA ================= --}}
    <div id="sectionDaftar">
        {{-- HEADER --}}
        <div class="mb-6 animate-fade-up">
            <h1 class="text-2xl font-bold text-gray-800">Peserta & Penilaian</h1>
            <p class="mt-1 text-sm text-gray-500">
                Login sebagai:
                <span class="font-semibold {{ $tipe === 'tertulis' ? 'text-purple-600' : 'text-blue-600' }}">
                    Penguji {{ ucfirst($tipe) }}
                </span>
            </p>
        </div>

        @if ($pesertas->isEmpty())
            <div class="rounded-xl bg-white p-10 text-center shadow-sm">
                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-3 text-sm text-gray-500">Belum ada peserta yang ditugaskan kepada Anda.</p>
            </div>
        @else
            <div class="rounded-xl bg-white shadow-sm">
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50 text-left">
                                <th class="px-6 py-4 font-semibold text-gray-600">No.</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Nama Peserta</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Instansi</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Jabatan</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Status Penilaian</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($pesertas as $i => $p)
                                @php
                                    $sudahDinilai = $p->penilaians->where('is_final', true)->count() > 0;
                                @endphp
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-500">{{ $i + 1 }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $p->nama }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $p->instansi }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $p->jabatan }}</td>
                                    <td class="px-6 py-4">
                                        @if ($sudahDinilai)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                                                </svg>
                                                Sudah Dinilai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Belum Dinilai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{-- PERBAIKAN: Tombol memanggil fungsi JS, bukan link href --}}
                                        <button type="button"
                                                onclick="bukaFormPenilaian('{{ $p->id }}', '{{ $p->nama }}', '{{ $p->instansi }}', '{{ $p->jabatan }}', {{ $sudahDinilai ? 'true' : 'false' }})"
                                                class="inline-flex items-center gap-1.5 rounded-lg px-4 py-1.5 text-xs font-semibold
                                                    {{ $sudahDinilai
                                                        ? 'border-2 border-blue-600 bg-white text-blue-600 hover:bg-blue-50'
                                                        : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                                            {{ $sudahDinilai ? 'Lihat / Edit' : 'Nilai' }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4">
                    {{-- PERBAIKAN: Gunakan $pesertas->count() --}}
                    <p class="text-xs text-gray-500">Total: {{ $pesertas->count() }} peserta</p>
                </div>
            </div>
        @endif
    </div>

    {{-- ================= FORM PENILAIAN ================= --}}
    <div id="sectionForm" class="hidden animate-fade-up">

        <nav class="mb-3 flex items-center gap-2 text-xs text-gray-500">
            <button type="button" onclick="kembaliKeDaftar()" class="hover:text-blue-600">Peserta & Penilaian</button>
            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">Penilaian Peserta</span>
        </nav>

        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Penilaian Peserta</h1>
            <button type="button" onclick="kembaliKeDaftar()"
                    class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-600 transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </button>
        </div>

        <div id="bannerSudahDinilai" class="mb-6 hidden">
            <div class="flex items-start gap-3 rounded-xl bg-green-50 border border-green-200 p-4">
                <div class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-green-500 text-white">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-green-800">Penilaian sudah diselesaikan</p>
                    <p class="text-xs text-green-700 mt-0.5">Anda hanya dapat melihat penilaian. Nilai tidak bisa diubah lagi.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="" id="formPenilaian">
            @csrf
            <input type="hidden" name="peserta_nama" id="inputPesertaNama" value="">

            <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">

                {{-- Info Peserta --}}
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-slate-100">
                            <svg class="h-9 w-9 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.686-8 6v2h16v-2c0-3.314-3.582-6-8-6z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 id="infoNamaPeserta" class="text-lg font-bold text-gray-800 truncate">Andi Pratama</h2>
                            <div class="mt-1 flex items-center gap-1.5 text-xs text-gray-500">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span>Instansi: <span id="infoInstansi" class="text-gray-700 font-medium">Kota Magelang</span></span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Jabatan: <span id="infoJabatan">Analis Kepegawaian</span></p>
                        </div>
                    </div>
                    <button type="button" class="mt-4 flex w-full items-center justify-center gap-2 rounded-lg border border-blue-500 py-2 text-xs font-semibold text-blue-600 transition hover:bg-blue-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Lihat Berkas Peserta
                    </button>
                </div>

                {{-- Nilai Anda --}}
                <div class="rounded-xl bg-white p-5 shadow-sm ring-2 ring-blue-100">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100">
                            <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.686-8 6v2h16v-2c0-3.314-3.582-6-8-6z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-800">Penguji {{ ucfirst($tipePenguji) }} (Anda)</p>
                            <p class="text-xs text-gray-500">{{ $namaPenguji }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs font-semibold text-blue-600 mb-1">Nilai Anda</p>
                        <div class="flex items-baseline gap-2">
                            <span id="nilaiAndaDisplay" class="text-2xl font-bold text-blue-700">0</span>
                            <span class="text-sm text-gray-400">/ 100</span>
                        </div>
                    </div>
                </div>

                {{-- Rekan Penguji (Live) --}}
                <div class="rounded-xl bg-white p-5 shadow-sm ring-2 ring-green-100">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.686-8 6v2h16v-2c0-3.314-3.582-6-8-6z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-800">Rekan Penguji</p>
                            <p class="text-xs text-gray-500">
                                {{ $rekanPenguji->nama ?? 'Belum ada rekan' }}
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-semibold text-green-700">
                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-500"></span>
                            Live
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs font-semibold text-green-600 mb-1">Nilai Rekan (real-time)</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-green-700">90</span>
                            <span class="text-sm text-gray-400">/ 100</span>
                        </div>
                        <p class="mt-2 text-[10px] text-gray-400 text-right">Terakhir diperbarui baru saja</p>
                    </div>
                </div>
            </div>

            @if ($tipePenguji === 'wawancara')
                <div class="mb-6 rounded-xl bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                        <div>
                            <h2 class="text-base font-bold text-gray-800">Form Penilaian Ujian Wawancara</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Bobot: 60% dari total nilai</p>
                        </div>
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Wawancara</span>
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
                                @php
                                    $wawancara = [
                                        ['Kemampuan Analisis', 'Kompetensi Inti', 'Pengetahuan tentang Bidang Pekerjaan'],
                                        ['', '', 'Kemampuan menulis dan publikasi'],
                                        ['Kemampuan Politis', 'Kompetensi Inti', 'Konteks Politik'],
                                        ['', '', 'Regulasi dan Legislasi'],
                                        ['', '', 'Komunikasi'],
                                        ['', '', 'Membangun jejaring'],
                                        ['', 'Kompetensi Spesialis', 'Presentasi'],
                                        ['', '', 'Konsultasi Publik'],
                                        ['', '', 'Partnership'],
                                        ['Kemampuan Analisis & Politis', 'Kompetensi Dasar', 'Manajemen Diri'],
                                        ['', '', 'Membangun Tim'],
                                    ];
                                @endphp

                                @foreach ($wawancara as $i => $k)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            @if ($k[0])<span class="font-semibold text-gray-800">{{ $k[0] }}</span>@endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($k[1])<span class="text-gray-600">{{ $k[1] }}</span>@endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">{{ $k[2] }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="number"
                                                   name="wawancara[{{ $i }}]"
                                                   class="input-nilai w-20 rounded-lg border border-gray-300 px-2 py-1.5 text-center text-sm font-semibold text-blue-700
                                                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                                                   min="0" max="100" placeholder="—">
                                        </td>
                                        <td class="px-4 py-3">
                                            <textarea rows="1" placeholder="Catatan..."
                                                      class="w-full min-w-[200px] resize-none rounded-lg border border-gray-300 px-2 py-1.5 text-xs
                                                             focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"></textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- TABEL TERTULIS --}}
            @if ($tipePenguji === 'tertulis')
                <div class="mb-6 rounded-xl bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                        <div>
                            <h2 class="text-base font-bold text-gray-800">Form Penilaian Ujian Tertulis</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Bobot: 40% dari total nilai</p>
                        </div>
                        <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">Tertulis</span>
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
                                @php
                                    $tertulis = [
                                        ['Kemampuan Analisis', 'Kompetensi Inti', 'Pengetahuan tentang substansi Kebijakan Publik'],
                                        ['', '', 'Metode Riset'],
                                        ['', '', 'Teknik dan Analisis Kebijakan'],
                                        ['', 'Kompetensi Spesialis', 'Penyusunan Saran Kebijakan'],
                                        ['Kemampuan Politis', 'Kompetensi Inti', 'Regulasi dan Legislasi'],
                                    ];
                                @endphp

                                @foreach ($tertulis as $i => $k)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            @if ($k[0])<span class="font-semibold text-gray-800">{{ $k[0] }}</span>@endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($k[1])<span class="text-gray-600">{{ $k[1] }}</span>@endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">{{ $k[2] }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="number"
                                                   name="tertulis[{{ $i }}]"
                                                   class="input-nilai w-20 rounded-lg border border-gray-300 px-2 py-1.5 text-center text-sm font-semibold text-purple-700
                                                          focus:border-purple-500 focus:ring-2 focus:ring-purple-200 focus:outline-none"
                                                   min="0" max="100" placeholder="—">
                                        </td>
                                        <td class="px-4 py-3">
                                            <textarea rows="1" placeholder="Catatan..."
                                                      class="w-full min-w-[200px] resize-none rounded-lg border border-gray-300 px-2 py-1.5 text-xs
                                                             focus:border-purple-500 focus:ring-2 focus:ring-purple-200 focus:outline-none"></textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

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
                    <button type="button"
                            class="flex items-center gap-2 rounded-lg border border-blue-500 px-6 py-2.5 text-sm font-semibold text-blue-600 transition hover:bg-blue-50">
                        Simpan Draft
                    </button>
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
    </div>

@endsection

@push('scripts')
<script>
    function bukaFormPenilaian(id, nama, instansi, jabatan, sudahDinilai) {
        document.getElementById('infoNamaPeserta').textContent = nama;
        document.getElementById('infoInstansi').textContent = instansi;
        document.getElementById('infoJabatan').textContent = jabatan;
        document.getElementById('inputPesertaNama').value = nama;

        // --- PERBAIKAN: Atur action form secara dinamis ---
        const form = document.getElementById('formPenilaian');
        form.action = `/peserta-penilaian/${id}/simpan`;
        // ---------------------------------------------------

        const banner = document.getElementById('bannerSudahDinilai');
        if (sudahDinilai) {
            banner.classList.remove('hidden');
        } else {
            banner.classList.add('hidden');
        }

        document.getElementById('sectionDaftar').classList.add('hidden');
        document.getElementById('sectionForm').classList.remove('hidden');
        hitungSemua();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function kembaliKeDaftar() {
        document.getElementById('sectionForm').classList.add('hidden');
        document.getElementById('sectionDaftar').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function hitungSemua() {
        const inputNilai = document.querySelectorAll('.input-nilai');
        const totalRata = document.getElementById('totalRata');
        const totalBar = document.getElementById('totalBar');
        const statusKelulusan = document.getElementById('statusKelulusan');
        const nilaiAndaDisplay = document.getElementById('nilaiAndaDisplay');

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

        if (nilaiAndaDisplay) {
            nilaiAndaDisplay.textContent = rata.toFixed(1).replace('.', ',');
        }

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
        const inputNilai = document.querySelectorAll('.input-nilai');
        inputNilai.forEach(input => input.addEventListener('input', hitungSemua));
        hitungSemua();

        // Logika untuk membuka form otomatis berdasarkan URL ?buka=Nama
        const urlParams = new URLSearchParams(window.location.search);
        const namaBuka = urlParams.get('buka');

        if (namaBuka) {
            // Karena tombol sekarang ada di dalam tabel, kita perlu mencari tombolnya
            const semuaTombol = document.querySelectorAll('#sectionDaftar button');
            let ditemukan = false;
            
            semuaTombol.forEach(function(tombol) {
                // Kita cek teks nama di baris yang sama
                const row = tombol.closest('tr');
                if (row) {
                    const namaPeserta = row.querySelector('td:nth-child(2)').textContent.trim();
                    if (namaPeserta === namaBuka && !ditemukan) {
                        ditemukan = true;
                        tombol.click(); // Klik tombol untuk membuka form
                    }
                }
            });
        }
    });
</script>
@endpush