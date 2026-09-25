@extends('layouts.app')

@section('title', isset($peserta) ? 'Edit Peserta' : 'Tambah Peserta')

@section('content')
    <div class="mb-6 animate-fade-up">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ isset($peserta) ? 'Edit Peserta' : 'Tambah Peserta' }}
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Peserta wajib punya 2 penguji (1 wawancara + 1 tertulis, atau 2 wawancara)
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ isset($peserta) ? route('admin.kelola-peserta.update', $peserta->id) : route('admin.kelola-peserta.store') }}"
          class="max-w-3xl space-y-4">

        @csrf
        @if (isset($peserta)) @method('PUT') @endif

        {{-- DATA PESERTA --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-bold text-gray-800 uppercase tracking-wider">Data Peserta</h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="form-label">Nama Peserta <span class="req">*</span></label>
                    <input type="text" name="nama" required
                           value="{{ old('nama', $peserta->nama ?? '') }}"
                           class="form-input"
                           placeholder="cth: Andi Pratama">
                </div>
                <div>
                    <label class="form-label">Instansi <span class="req">*</span></label>
                    <input type="text" name="instansi" required
                           value="{{ old('instansi', $peserta->instansi ?? '') }}"
                           class="form-input"
                           placeholder="cth: Kota Magelang">
                </div>
                <div>
                    <label class="form-label">Jabatan <span class="req">*</span></label>
                    <input type="text" name="jabatan" required
                           value="{{ old('jabatan', $peserta->jabatan ?? '') }}"
                           class="form-input"
                           placeholder="cth: Analis Kepegawaian">
                </div>
            </div>
        </div>

        {{-- TIPE UJIAN --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-sm font-bold text-gray-800 uppercase tracking-wider">Tipe Ujian</h2>

            <label class="flex items-start gap-3 cursor-pointer rounded-xl border-2 border-gray-200 p-4 transition hover:border-blue-300 hover:bg-blue-50/40"
                   id="labelTertulis">
                <input type="checkbox" name="butuh_tertulis" id="butuhTertulis" value="1"
                       class="form-checkbox mt-0.5"
                       {{ old('butuh_tertulis', $peserta->butuh_tertulis ?? false) ? 'checked' : '' }}>
                <div>
                    <p class="text-sm font-semibold text-gray-800">
                        Peserta ini butuh <span class="text-purple-600">Ujian Tertulis</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Jika dicentang, peserta akan dinilai oleh 1 penguji wawancara + 1 penguji tertulis.
                        Jika tidak, peserta dinilai oleh 2 penguji wawancara.
                    </p>
                </div>
            </label>
        </div>

        {{-- PENGUJI WAWANCARA --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-blue-100">
            <div class="mb-4 flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-800">Penguji Wawancara</h2>
                    <p class="text-xs text-gray-500">Wajib 1, bisa 2 jika tidak butuh tertulis</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="form-label">Penguji Wawancara 1 <span class="req">*</span></label>
                    <select name="penguji_wawancara[]" required class="form-select">
                        <option value="">-- Pilih Penguji --</option>
                        @foreach ($pengujiWawancara as $pj)
                            <option value="{{ $pj->id }}"
                                {{ in_array($pj->id, old('penguji_wawancara', $assignedWawancara ?? [])) ? 'selected' : '' }}>
                                {{ $pj->nama }} — {{ $pj->kelompok ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Penguji Wawancara 2</label>
                    <select name="penguji_wawancara[]" class="form-select" id="selectWawancara2">
                        <option value="">-- Kosongkan jika butuh tertulis --</option>
                        @foreach ($pengujiWawancara as $pj)
                            <option value="{{ $pj->id }}"
                                {{ in_array($pj->id, old('penguji_wawancara', $assignedWawancara ?? [])) ? 'selected' : '' }}>
                                {{ $pj->nama }} — {{ $pj->kelompok ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- PENGUJI TERTULIS --}}
        <div id="blokTertulis"
             class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-purple-100 {{ old('butuh_tertulis', $peserta->butuh_tertulis ?? false) ? '' : 'hidden' }}">
            <div class="mb-4 flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-100">
                    <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-800">Penguji Tertulis</h2>
                    <p class="text-xs text-gray-500">Wajib 1 penguji tertulis</p>
                </div>
            </div>

            <div>
                <label class="form-label">Pilih Penguji Tertulis <span class="req">*</span></label>
                <select name="penguji_tertulis" class="form-select" id="selectTertulis">
                    <option value="">-- Pilih Penguji Tertulis --</option>
                    @foreach ($pengujiTertulis as $pj)
                        <option value="{{ $pj->id }}"
                            {{ old('penguji_tertulis', $assignedTertulis ?? '') == $pj->id ? 'selected' : '' }}>
                            {{ $pj->nama }} — {{ $pj->kelompok ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- STATUS AKTIF (edit mode) --}}
        @if (isset($peserta))
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="form-checkbox"
                           {{ $peserta->is_active ? 'checked' : '' }}>
                    <span class="text-sm font-semibold text-gray-700">Peserta Aktif</span>
                </label>
            </div>
        @endif

        {{-- TOMBOL --}}
        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('admin.kelola-peserta') }}"
               class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit"
                    class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-600/30 hover:bg-blue-700">
                {{ isset($peserta) ? 'Update Peserta' : 'Simpan Peserta' }}
            </button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    const checkboxTertulis = document.getElementById('butuhTertulis');
    const blokTertulis = document.getElementById('blokTertulis');
    const selectWawancara2 = document.getElementById('selectWawancara2');
    const selectTertulis = document.getElementById('selectTertulis');

    function toggleTertulis() {
        const aktif = checkboxTertulis.checked;
        blokTertulis.classList.toggle('hidden', !aktif);

        // Kalau butuh tertulis → select wawancara 2 boleh kosong, tertulis wajib
        // Kalau tidak butuh tertulis → select wawancara 2 wajib, tertulis kosong
        selectWawancara2.required = !aktif;
        if (selectTertulis) selectTertulis.required = aktif;

        if (aktif && selectWawancara2) {
            // Kosongkan pilihan wawancara 2 saat butuh tertulis
            // (opsional, bisa dihapus kalau tidak mau auto-clear)
        }
    }

    checkboxTertulis.addEventListener('change', toggleTertulis);
    toggleTertulis();
</script>
@endpush