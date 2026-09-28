@php
    $namaUser   = session('nama_penguji', 'User');
    $emailUser  = session('email', '-');
    $roleUser   = session('role', 'penguji');
    $tipeUser   = session('tipe_penguji', '-');
    $subtitle   = $roleUser === 'admin' ? 'Administrator' : 'Penguji ' . ucfirst($tipeUser);
@endphp

<header class="sticky top-0 z-20 flex items-center justify-between bg-white px-6 py-4 shadow-sm">
    <button id="sidebarToggle" class="text-gray-500 hover:text-gray-800 lg:hidden">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <div class="hidden lg:block"></div>

    <div class="flex items-center gap-6">
        <div class="relative">
            <button id="userMenuButton" class="flex items-center gap-3 rounded-lg px-2 py-1.5 transition hover:bg-gray-50">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.686-8 6v2h16v-2c0-3.314-3.582-6-8-6z"/>
                    </svg>
                </div>
                <div class="hidden text-left sm:block">
                    <p class="text-sm font-bold text-gray-800">{{ $namaUser }}</p>
                    <p class="text-xs text-gray-500">{{ $subtitle }}</p>
                </div>
                <svg id="chevronIcon" class="h-4 w-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="userDropdown"
                 class="absolute right-0 top-full mt-2 hidden w-56 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg">
                <div class="border-b border-gray-100 px-4 py-3">
                    <p class="text-sm font-bold text-gray-800">{{ $namaUser }}</p>
                    <p class="text-xs text-gray-500">{{ $emailUser }}</p>
                </div>

                @if ($roleUser !== 'admin')
                    <a href="{{ route('profil.penguji') }}"
                       class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Saya
                    </a>

                    <a href="#"
                       class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Pengaturan
                    </a>

                    <div class="border-t border-gray-100"></div>
                @endif

                <form method="POST" action="{{ route('logout.penguji') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-red-600 transition hover:bg-red-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userButton = document.getElementById('userMenuButton');
        const dropdown = document.getElementById('userDropdown');
        const chevron = document.getElementById('chevronIcon');

        if (userButton && dropdown) {
            userButton.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');

                if (chevron) {
                    chevron.classList.toggle('rotate-180');
                }
            });

            document.addEventListener('click', function() {
                dropdown.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            });

            dropdown.addEventListener('click', function(e) {
                if (!e.target.closest('a') && !e.target.closest('button')) {
                    e.stopPropagation();
                }
            });
        }
    });
</script>