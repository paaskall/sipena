@extends('layouts.app')

@section('title', 'Edit Nilai - LAN RI')

@section('content')
    <div class="mb-6 animate-fade-up">
        <h1 class="text-2xl font-bold text-gray-800">Edit Nilai Peserta</h1>
        <p class="mt-1 text-sm text-gray-500">Admin dapat mengedit nilai yang sudah diinput penguji</p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4 rounded-xl bg-white p-4 shadow-sm">
        <label class="text-xs font-semibold text-gray-600">Pilih Peserta</label>
        <form method="GET" action="{{ route('admin.edit-nilai') }}">
            <select name="peserta_id" onchange="this.form.submit()"
                    class="mt-1 w-full max-w-md rounded-lg border-gray-300 px-3 py-2 text-sm">
                @foreach ($pesertaList as $p)
                    <option value="{{ $p->id }}" {{ ($peserta->id ?? '') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama }} — {{ $p->instansi }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if ($peserta)
        @foreach (['wawancara', 'tertulis'] as $tipe)
            @php
                $penilaians = $peserta->penilaians->where('tipe', $tipe)->sortBy('elemen_index');
            @endphp

            @if ($penilaians->count() > 0)
                <div class="mb-6 rounded-xl bg-white shadow-sm">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-base font-bold text-gray-800">Nilai {{ ucfirst($tipe) }}</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b bg-gray-50 text-left">
                                    <th class="px-4 py-3 font-semibold text-gray-600">Elemen</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600">Penguji</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600">Nilai</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600">Catatan</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600">Diedit Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penilaians as $n)
                                    <tr class="border-b">
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $n->elemen_kompetensi }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $n->penguji->nama ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            <form method="POST" action="{{ route('admin.edit-nilai.update') }}" class="flex gap-2">
                                                @csrf
                                                <input type="hidden" name="penilaian_id" value="{{ $n->id }}">
                                                <input type="number" name="nilai" value="{{ $n->nilai }}" min="0" max="100" step="0.01"
                                                       class="w-20 rounded border-gray-300 px-2 py-1 text-sm">
                                                <button class="rounded bg-blue-600 px-3 py-1 text-xs font-semibold text-white hover:bg-blue-700">
                                                    Simpan
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" name="catatan" value="{{ $n->catatan }}" form=""
                                                   class="w-full rounded border-gray-300 px-2 py-1 text-xs" disabled>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500">
                                            @if ($n->edited_by_admin_at)
                                                {{ $n->edited_by_admin_at->format('d/m/Y H:i') }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
@endsection