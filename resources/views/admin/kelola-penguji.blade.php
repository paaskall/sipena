@extends('layouts.app')

@section('title', 'Kelola Penguji - LAN RI')

@section('content')
    <div class="mb-6 animate-fade-up">
        <h1 class="text-2xl font-bold text-gray-800">Kelola Penguji</h1>
        <p class="mt-1 text-sm text-gray-500">Tambah, edit, dan hapus akun penguji</p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <h2 class="text-base font-bold text-gray-800">Daftar Penguji</h2>
            <button onclick="openModalTambah()"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                + Tambah Penguji
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50 text-left">
                        <th class="px-4 py-3 font-semibold text-gray-600">No</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Nama</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Email / Username</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Tipe</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Kelompok</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengujis as $i => $p)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $p->nama }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $p->email }}<br>
                                <span class="text-xs text-gray-400">{{ $p->username }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $p->tipe_penguji === 'wawancara' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ ucfirst($p->tipe_penguji) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $p->kelompok ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if ($p->is_active)
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Aktif</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <button onclick='openModalEdit(@json($p))'
                                            class="rounded-lg border border-blue-500 px-3 py-1 text-xs font-semibold text-blue-600 hover:bg-blue-50">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.kelola-penguji.destroy', $p->id) }}"
                                          onsubmit="return confirm('Yakin hapus penguji ini?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg border border-red-500 px-3 py-1 text-xs font-semibold text-red-600 hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada penguji</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="modalTambah" class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
        <div class="modal-card w-full max-w-2xl rounded-2xl bg-white shadow-2xl">
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Tambah Penguji</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Lengkapi data penguji baru</p>
                </div>
                <button type="button" onclick="closeModal('modalTambah')"
                        class="flex h-8 w-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <form method="POST" action="{{ route('admin.kelola-penguji.store') }}">
                @csrf
                <div class="max-h-[70vh] overflow-y-auto px-6 py-5">
                    @include('admin.partials.penguji-form-fields', ['penguji' => null])
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2 border-t border-gray-100 px-6 py-4">
                    <button type="button" onclick="closeModal('modalTambah')"
                            class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-blue-600/30 hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modalEdit" class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
        <div class="modal-card w-full max-w-2xl rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Edit Penguji</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Ubah data penguji</p>
                </div>
                <button type="button" onclick="closeModal('modalEdit')"
                        class="flex h-8 w-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" id="formEdit" action="">
                @csrf @method('PUT')
                <div id="editFields" class="max-h-[70vh] overflow-y-auto px-6 py-5"></div>

                <div class="flex justify-end gap-2 border-t border-gray-100 px-6 py-4">
                    <button type="button" onclick="closeModal('modalEdit')"
                            class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-blue-600/30 hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Animasi modal masuk */
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-card { animation: modalIn 0.2s ease-out; }

    /* Scrollbar halus */
    .modal-card ::-webkit-scrollbar { width: 6px; }
    .modal-card ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
</style>
@endpush

@push('scripts')
<script>
    function openModalTambah() {
        const m = document.getElementById('modalTambah');
        m.classList.remove('hidden'); m.classList.add('flex');
    }
    function closeModal(id) {
        const m = document.getElementById(id);
        m.classList.add('hidden'); m.classList.remove('flex');
    }

    // Tutup modal kalau klik area gelap
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) overlay.classList.add('hidden'), overlay.classList.remove('flex');
        });
    });

    function openModalEdit(p) {
        const m = document.getElementById('modalEdit');
        document.getElementById('formEdit').action = `/admin/kelola-penguji/${p.id}`;
        document.getElementById('editFields').innerHTML = `
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" name="nama" value="${p.nama}" required class="form-input" placeholder="cth: Dr. Budi Santoso, M.Si">
                </div>
                <div>
                    <label class="form-label">Email <span class="req">*</span></label>
                    <input type="email" name="email" value="${p.email}" required class="form-input" placeholder="email@lanri.go.id">
                </div>
                <div>
                    <label class="form-label">Username <span class="req">*</span></label>
                    <input type="text" name="username" value="${p.username}" required class="form-input" placeholder="username">
                </div>
                <div>
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak diubah">
                </div>
                <div>
                    <label class="form-label">Tipe Penguji <span class="req">*</span></label>
                    <select name="tipe_penguji" class="form-select">
                        <option value="wawancara" ${p.tipe_penguji==='wawancara'?'selected':''}>Wawancara</option>
                        <option value="tertulis" ${p.tipe_penguji==='tertulis'?'selected':''}>Tertulis</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" value="${p.nip ?? ''}" class="form-input" placeholder="Nomor Induk Pegawai">
                </div>
                <div>
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" value="${p.no_hp ?? ''}" class="form-input" placeholder="08xxxxxxxxxx">
                </div>
                <div>
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" value="${p.jabatan ?? ''}" class="form-input" placeholder="cth: Penguji Ahli">
                </div>
                <div>
                    <label class="form-label">Instansi</label>
                    <input type="text" name="instansi" value="${p.instansi ?? ''}" class="form-input" placeholder="cth: LAN RI">
                </div>
                <div>
                    <label class="form-label">Kelompok</label>
                    <input type="text" name="kelompok" value="${p.kelompok ?? ''}" class="form-input" placeholder="cth: A">
                </div>
                <div class="sm:col-span-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" ${p.is_active?'checked':''}
                               class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Akun Aktif</span>
                    </label>
                </div>
            </div>
        `;
        m.classList.remove('hidden'); m.classList.add('flex');
    }
</script>
@endpush