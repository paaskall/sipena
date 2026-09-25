@extends('layouts.app')

@section('title', 'Admin Dashboard - LAN RI')

@section('content')
    <div class="mb-6 animate-fade-up">
        <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500">
            Selamat datang, <span class="font-semibold text-blue-600">{{ session('nama_penguji', 'Admin') }}</span>!
        </p>
    </div>

    @if (session('success'))
        <div class="mb-4 flex items-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 animate-fade-up">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
        <div class="animate-fade-up delay-100 rounded-xl bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-gray-500">Total Penguji</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalPenguji }}</p>
        </div>
        <div class="animate-fade-up delay-200 rounded-xl bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-gray-500">Total Peserta</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalPeserta }}</p>
        </div>
        <div class="animate-fade-up delay-300 rounded-xl bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-gray-500">Total Penilaian</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalPenilaian }}</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm animate-fade-up delay-200">
        <h2 class="text-base font-bold text-gray-800 mb-4">Menu Admin</h2>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Kelola Penguji --}}
            <a href="{{ route('admin.kelola-penguji') }}"
               class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 transition hover:bg-blue-50 hover:border-blue-300">
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Kelola Penguji</span>
            </a>

            {{-- Kelola Peserta --}}
            <a href="{{ route('admin.kelola-peserta') }}"
               class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 transition hover:bg-blue-50 hover:border-blue-300">
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Kelola Peserta</span>
            </a>

            {{-- Rekap Nilai --}}
            <a href="{{ route('admin.rekap-nilai') }}"
               class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 transition hover:bg-blue-50 hover:border-blue-300">
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Lihat Rekap Nilai</span>
            </a>

            {{-- Edit Nilai --}}
            <a href="{{ route('admin.edit-nilai') }}"
               class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 transition hover:bg-blue-50 hover:border-blue-300">
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Edit Nilai</span>
            </a>

            {{-- Kelola User --}}
            <a href="{{ route('admin.kelola-user') }}"
               class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 transition hover:bg-blue-50 hover:border-blue-300">
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Kelola User</span>
            </a>

            {{-- Export CSV --}}
            <a href="{{ route('admin.export.csv') }}"
               class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 transition hover:bg-green-50 hover:border-green-300">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Export CSV</span>
            </a>

            {{-- Export PDF --}}
            <a href="{{ route('admin.export.pdf') }}"
               class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 transition hover:bg-red-50 hover:border-red-300">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-700">Export PDF</span>
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout.penguji') }}">
                @csrf
                <button type="submit"
                        class="flex w-full flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 transition hover:bg-red-50 hover:border-red-300">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Keluar</span>
                </button>
            </form>
        </div>
    </div>
@endsection