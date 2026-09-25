@extends('layouts.app')

@section('title', 'Kelola User - LAN RI')

@section('content')
    <div class="mb-6 animate-fade-up">
        <h1 class="text-2xl font-bold text-gray-800">Kelola User</h1>
        <p class="mt-1 text-sm text-gray-500">Semua akun admin dan penguji</p>
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
                    <th class="px-4 py-3 font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Role</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Tipe</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $i => $u)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $u->nama }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $u->email }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold
                                {{ $u->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $u->tipe_penguji }}</td>
                        <td class="px-4 py-3">
                            @if ($u->is_active)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Aktif</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <form method="POST" action="{{ route('admin.kelola-user.toggle', $u->id) }}">
                                    @csrf
                                    <button class="rounded-lg border border-yellow-500 px-3 py-1 text-xs font-semibold text-yellow-600 hover:bg-yellow-50">
                                        {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.kelola-user.reset', $u->id) }}"
                                      onsubmit="return confirm('Reset password jadi: password123?')">
                                    @csrf
                                    <button class="rounded-lg border border-blue-500 px-3 py-1 text-xs font-semibold text-blue-600 hover:bg-blue-50">
                                        Reset Password
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection