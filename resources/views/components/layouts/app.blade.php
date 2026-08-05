@props(['title' => 'Dashboard', 'navigation' => []])

@php
    $navigationByRole = [
        'Kesiswaan' => [
            ['kesiswaan.dashboard', 'Statistik sekolah', 'dashboard'],
            ['kesiswaan.reports', 'Laporan masuk', 'file'],
            ['kesiswaan.letters', 'Surat panggilan', 'letter'],
        ],
        'Guru BK' => [
            ['guru.dashboard', 'Ringkasan', 'dashboard'],
            ['guru.records', 'Catatan poin', 'note'],
            ['guru.students', 'Rekap siswa', 'users'],
            ['guru.reports', 'Laporan kesiswaan', 'file'],
            ['guru.letters', 'Surat panggilan', 'letter'],
        ],
        'Guru Pelapor' => [
            ['guru.records', 'Catatan poin', 'note'],
        ],
        'Wali Kelas' => [
            ['wali-kelas.dashboard', 'Ringkasan kelas', 'dashboard'],
            ['wali-kelas.students', 'Daftar siswa', 'users'],
            ['wali-kelas.notifications', 'Notifikasi', 'bell'],
        ],
        'Siswa' => [
            ['siswa.dashboard', 'Ringkasan', 'dashboard'],
            ['siswa.history', 'Riwayat poin', 'clock'],
            ['siswa.notifications', 'Notifikasi', 'bell'],
        ],
    ];

    $navigation = count($navigation)
        ? $navigation
        : ($navigationByRole[auth()->user()->role->nama_role] ?? []);

    $lucideIcons = [
        'alert' => 'circle-alert',
        'bell' => 'bell',
        'clock' => 'clock-3',
        'dashboard' => 'layout-dashboard',
        'file' => 'file-text',
        'heart' => 'heart',
        'letter' => 'mail',
        'list' => 'list',
        'logout' => 'log-out',
        'menu' => 'menu',
        'note' => 'notebook-pen',
        'school' => 'school',
        'tracking' => 'chart-no-axes-combined',
        'user' => 'user',
        'users' => 'users',
    ];

    $flashMessage = null;

    if (session('success')) {
        $flashMessage = ['type' => 'success', 'message' => session('success')];
    } elseif ($errors->any()) {
        $flashMessage = ['type' => 'error', 'message' => $errors->first()];
    }
@endphp

<!doctype html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | POINTKU</title>
    <link rel="icon" href="{{ asset('Logo_SMAN_1_Tasikmalaya.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('Logo_SMAN_1_Tasikmalaya.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-[#4a1c1c] antialiased">
    @include('partials.icons')

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="app-sidebar" 
               class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col bg-[#6d1a1a] text-[#fce4c4] shadow-2xl transition-transform duration-200 max-[1050px]:-translate-x-full" 
               aria-label="Navigasi utama">
            
            <!-- Sidebar Header (Fixed) -->
            <div class="flex shrink-0 items-center justify-between px-5 pt-6 pb-4">
                <a class="flex items-center gap-3 px-1" href="{{ route('dashboard') }}">
                    <img src="{{ asset('Logo_SMAN_1_Tasikmalaya.png') }}" alt="Logo SMAN 1 Tasikmalaya" class="h-9 w-auto">
                    <span>
                        <strong class="block text-lg font-bold leading-tight">POINTKU</strong>
                        <small class="block text-[10px] font-medium text-[#e6b98a] tracking-wide">student care system</small>
                    </span>
                </a>
            </div>

            <!-- Navigation Area (Scrollable) -->
            <div class="flex-1 overflow-y-auto px-4 py-2 space-y-6">
                <!-- Ruang Kerja -->
                <div>
                    <p class="mb-2 px-3 text-[10px] font-extrabold tracking-widest text-[#e6b98a] uppercase">RUANG KERJA</p>
                    <nav class="space-y-1">
                        @foreach ($navigation as $item)
                            @php
                                $routeName = is_array($item) ? ($item['route'] ?? $item[0] ?? '') : '';
                                $label = is_array($item) ? ($item['label'] ?? $item[1] ?? '') : '';
                                $icon = is_array($item) ? ($item['icon'] ?? $item[2] ?? 'list') : 'list';
                            @endphp
                            @if($routeName)
                                <a class="relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-xs font-semibold text-[#fce4c4] transition-colors hover:bg-white/10 {{ request()->routeIs($routeName.'*') ? 'bg-white/10 text-white' : '' }}" href="{{ route($routeName) }}">
                                    <i data-lucide="{{ $lucideIcons[$icon] ?? 'list' }}" class="h-5 w-5 shrink-0"></i>
                                    <span class="truncate">{{ $label }}</span>
                                    @if(request()->routeIs($routeName.'*'))
                                        <i class="absolute right-0 h-5 w-1 rounded-l bg-[#fbc02d]"></i>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    </nav>
                </div>

                <!-- Data Master -->
                @if(auth()->user()->hasRole('Kesiswaan'))
                    <div>
                        <p class="mb-2 px-3 text-[10px] font-extrabold tracking-widest text-[#e6b98a] uppercase">DATA MASTER</p>
                        <nav class="space-y-1">
                            @foreach ([
                                ['kesiswaan.master.users', 'Pengguna', 'user'],
                                ['kesiswaan.master.classes', 'Kelas', 'school'],
                                ['kesiswaan.master.students', 'Siswa', 'users'],
                                ['kesiswaan.master.categories', 'Kategori poin', 'list'],
                                ['kesiswaan.master.thresholds', 'Threshold', 'tracking'],
                            ] as $item)
                                <a class="relative flex items-center gap-3 rounded-lg px-3 py-2 text-xs font-semibold text-[#fce4c4] transition-colors hover:bg-white/10 {{ request()->routeIs($item[0].'*') ? 'bg-white/10 text-white' : '' }}" href="{{ route($item[0]) }}">
                                    <i data-lucide="{{ $lucideIcons[$item[2]] ?? 'list' }}" class="h-4 w-4 shrink-0"></i>
                                    <span class="truncate">{{ $item[1] }}</span>
                                    @if(request()->routeIs($item[0].'*'))
                                        <i class="absolute right-0 h-5 w-1 rounded-l bg-[#fbc02d]"></i>
                                    @endif
                                </a>
                            @endforeach
                        </nav>
                    </div>
                @endif

                <!-- Card Info -->
                <div class="flex items-start gap-3 rounded-xl border border-white/10 bg-white/5 p-3.5 text-xs text-[#e6c8a8]">
                    <i data-lucide="heart" class="h-5 w-5 shrink-0 mt-0.5"></i>
                    <div>
                        <strong class="block font-semibold text-white">Budaya positif</strong>
                        <span class="text-[11px] text-[#e6c8a8]/80 leading-snug block mt-0.5">Catat kebaikan sekecil apa pun.</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar Footer (Fixed) -->
            <div class="shrink-0 border-t border-white/10 p-4">
                <div class="flex items-center gap-3">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-[#fbc02d] text-sm font-extrabold text-white">
                        {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
                    </span>
                    <span class="min-w-0 flex-1">
                        <strong class="block truncate text-xs font-bold text-white">{{ auth()->user()->nama_lengkap }}</strong>
                        <small class="block truncate text-[11px] text-[#e6b98a]">{{ auth()->user()->role->nama_role }}</small>
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                        @csrf
                        <button class="grid h-8 w-8 place-items-center rounded-lg text-[#e6b98a] transition-colors hover:bg-white/10 hover:text-white" aria-label="Keluar">
                            <i data-lucide="log-out" class="h-5 w-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="ml-64 flex-1 min-w-0 max-[1050px]:ml-0">
            <!-- Header -->
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-[#fce4c4] bg-white/95 px-6 backdrop-blur max-[760px]:px-4">
                <div class="flex items-center gap-4">
                    <button id="menu-button" class="hidden rounded-lg p-1.5 text-[#4a1c1c] hover:bg-gray-100 max-[1050px]:grid" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="app-sidebar">
                        <i data-lucide="menu" class="h-6 w-6"></i>
                    </button>
                    <div class="text-sm font-medium text-gray-500">
                        <span>POINTKU / </span>
                        <strong class="font-bold text-[#4a1c1c]">{{ $title }}</strong>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-xs text-[#8d6e63] max-[760px]:hidden">{{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="hidden h-8 w-8 place-items-center rounded-full bg-[#fbc02d] text-xs font-extrabold text-white max-[1050px]:grid">
                        {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
                    </span>
                </div>
            </header>

            <!-- Content Section -->
            <section class="mx-auto max-w-7xl p-6 max-[760px]:px-4 max-[760px]:py-5">
                {{ $slot }}
            </section>
        </main>
    </div>

    <!-- Backdrop Overlay for Mobile -->
    <button id="sidebar-scrim" class="fixed inset-0 z-20 hidden bg-[#4a1c1c]/40 backdrop-blur-sm" type="button" aria-label="Tutup menu"></button>

    <script>
        window.flashMessage = {{ Illuminate\Support\Js::from($flashMessage) }};

        const sidebar = document.getElementById('app-sidebar');
        const menuButton = document.getElementById('menu-button');
        const scrim = document.getElementById('sidebar-scrim');

        const setSidebar = (open) => {
            sidebar.classList.toggle('translate-x-0', open);
            sidebar.classList.toggle('max-[1050px]:-translate-x-full', !open);
            scrim.classList.toggle('hidden', !open);
            menuButton?.setAttribute('aria-expanded', String(open));
        };

        menuButton?.addEventListener('click', () => setSidebar(true));
        scrim?.addEventListener('click', () => setSidebar(false));
    </script>
</body>
</html>
