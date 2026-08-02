@props(['title' => 'Dashboard', 'navigation' => []])

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | POINKU</title>
    @vite('resources/css/app.css')
</head>
    <body class="m-0 bg-white font-sans text-[#4a1c1c] antialiased">
    @include('partials.icons')

    <div class="flex min-h-screen">
        <aside id="app-sidebar" class="fixed inset-y-0 left-0 z-30 flex w-[260px] flex-col bg-[#6d1a1a] px-[1.15rem] py-6 text-[#fce4c4] shadow-[20px_0_50px_#4a1c1c33] transition-transform duration-200 max-[1050px]:-translate-x-full" aria-label="Navigasi utama">
            <div class="flex items-center justify-between">
                <a class="flex items-center gap-3 px-2 py-0.5" href="{{ route('dashboard') }}">
                    <img src="{{ asset('Logo_SMAN_1_Tasikmalaya.png') }}" alt="Logo SMAN 1 Tasikmalaya" class="block h-[36px] w-auto">
                    <span>
                        <strong>POINKU</strong>
                        <small>student care system</small>
                    </span>
                </a>
            </div>

            <p class="my-[2.2rem] mb-3 ml-3 text-[.62rem] font-extrabold tracking-[.18em] text-[#e6b98a]">RUANG KERJA</p>
            <nav class="grid gap-1">
                @foreach ($navigation as $item)
                    <a class="relative flex min-h-[46px] items-center gap-3 overflow-hidden rounded-[9px] px-[.85rem] py-3 text-[.8rem] font-semibold text-[#fce4c4] hover:bg-white/[.08] {{ request()->routeIs($item[0].'*') ? 'bg-white/[.08] text-white' : '' }}" href="{{ route($item[0]) }}">
                        <svg>
                            <use href="#icon-{{ $item[2] ?? 'list' }}"></use>
                        </svg>
                        <span>{{ $item[1] }}</span>
                        <i class="absolute right-0 h-[22px] w-[3px] rounded-l-[4px] {{ request()->routeIs($item[0].'*') ? 'bg-[#fbc02d]' : 'bg-transparent' }}"></i>
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto mb-4 flex gap-3 rounded-xl border border-white/[.06] bg-white/[.03] p-4 text-[#e6c8a8]">
                <svg>
                    <use href="#icon-heart"></use>
                </svg>
                <div>
                    <strong>Budaya positif</strong>
                    <span>Catat kebaikan sekecil apa pun.</span>
                </div>
            </div>

            <div class="flex items-center gap-3 border-t border-white/[.06] px-1 py-4">
                <span class="grid h-[35px] w-[35px] shrink-0 place-items-center rounded-full bg-[#fbc02d] font-extrabold text-white">{{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}</span>
                <span class="min-w-0">
                    <strong>{{ auth()->user()->nama_lengkap }}</strong>
                    <small>{{ auth()->user()->role->nama_role }}</small>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="ml-auto grid place-items-center border-0 bg-transparent text-[#e6b98a]" aria-label="Keluar">
                        <svg>
                            <use href="#icon-logout"></use>
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        <main class="ml-[260px] min-w-0 w-[calc(100%-260px)] max-[1050px]:ml-0 max-[1050px]:w-full">
            <header class="flex h-[76px] items-center justify-between border-b border-[#fce4c4] bg-white px-[clamp(1.25rem,3.5vw,3.8rem)] max-[760px]:h-[65px] max-[760px]:px-4">
                <div class="flex items-center gap-5">
                    <button id="menu-button" class="hidden border-0 bg-transparent max-[1050px]:grid" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="app-sidebar">
                        <svg>
                            <use href="#icon-menu"></use>
                        </svg>
                    </button>
                    <div>
                        <span>POINKU / </span>
                        <strong>{{ $title }}</strong>
                    </div>
                </div>
                <div class="flex items-center gap-5">
                    <span class="text-[.72rem] text-[#8d6e63] max-[760px]:hidden">{{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="hidden h-[34px] w-[34px] place-items-center rounded-full bg-[#fbc02d] text-[.75rem] font-extrabold text-white max-[1050px]:grid">{{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}</span>
                </div>
            </header>

            <section class="max-w-[1500px] min-h-[calc(100vh-76px)] p-[clamp(1.5rem,3.5vw,3.8rem)] max-[760px]:px-4 max-[760px]:py-6">
                @if (session('success'))
                    <div class="flex items-center gap-[.7rem] mb-[1.2rem] rounded-[11px] border border-[#ffe0b2] bg-[#fff8e1] p-[1rem] text-[.8rem] font-[750] text-[#5d4037]">
                        <svg>
                            <use href="#icon-check"></use>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="flex items-center gap-[.7rem] mb-[1.2rem] rounded-[11px] border border-[#ffcdd2] bg-[#ffebee] p-[1rem] text-[.8rem] font-[750] text-[#b71c1c]">
                        <svg>
                            <use href="#icon-alert"></use>
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                {{ $slot }}
            </section>
        </main>
    </div>
    <button id="sidebar-scrim" class="fixed inset-0 z-20 hidden border-0 bg-[#4a1c1c6e]" type="button" aria-label="Tutup menu"></button>
    <script>
        const sidebar = document.getElementById('app-sidebar');
        const menuButton = document.getElementById('menu-button');
        const scrim = document.getElementById('sidebar-scrim');
        const setSidebar = (open) => {
            sidebar.classList.toggle('translate-x-0', open);
            sidebar.classList.toggle('max-[1050px]:-translate-x-full', !open);
            scrim.classList.toggle('hidden', !open);
            menuButton.setAttribute('aria-expanded', String(open));
        };
        menuButton?.addEventListener('click', () => setSidebar(true));
        scrim?.addEventListener('click', () => setSidebar(false));
    </script>
</body>
</html>
