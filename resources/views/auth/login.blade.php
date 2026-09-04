<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | POINTKU</title>
    <link rel="icon" href="{{ asset('Logo_SMAN_1_Tasikmalaya.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('Logo_SMAN_1_Tasikmalaya.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 min-[761px]:bg-white">
    <main class="flex min-h-screen items-center justify-center p-4 min-[761px]:grid min-[761px]:grid-cols-[minmax(420px,1.08fr)_minmax(420px,.92fr)] min-[761px]:p-0 min-[761px]:bg-white">
        
        <!-- Sidebar kiri (Desktop) -->
        <section class="relative hidden min-h-screen flex-col overflow-hidden bg-[linear-gradient(145deg,#6d1a1a,#4a1c1c_72%)] p-8 text-white before:absolute before:inset-auto-[-13vw] before:-bottom-[22vw] before:h-[52vw] before:w-[52vw] before:rounded-full before:border before:border-white/[.07] before:shadow-[0_0_0_7vw_#ffffff06,0_0_0_14vw_#ffffff04] after:absolute after:right-[8%] after:top-[17%] after:h-[190px] after:w-[190px] after:rounded-full after:bg-[radial-gradient(circle,#fbc02d_0_2px,transparent_3px)] after:bg-[length:18px_18px] after:opacity-35 min-[761px]:flex min-[761px]:p-[clamp(2rem,4vw,4.5rem)]">
            <div class="relative z-[1] flex items-center justify-between">
                <a class="flex items-center gap-[.8rem]" href="/">
                    <img src="{{ asset('Logo_SMAN_1_Tasikmalaya.png') }}" alt="Logo SMAN 1 Tasikmalaya" class="block h-[38px] w-auto">
                    <span>
                        <strong class="block text-[1.15rem] font-bold tracking-[.03em]">POINTKU</strong>
                        <small class="mt-[.2rem] block text-[.65rem] text-[#e6b98a]">student care system</small>
                    </span>
                </a>
                <span class="rounded-[99px] border border-white/[.12] px-[.85rem] py-[.55rem] text-[.68rem] uppercase tracking-[.12em] text-[#ffccbc]">Sistem Kesiswaan</span>
            </div>
            
            <div class="relative z-[1] mb-12 mt-auto max-w-[680px]">
                <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#9e5a5a]">TERTIB. PEDULI. BERTUMBUH.</p>
                <h1 class="mb-[1.6rem] text-[clamp(3.2rem,5.8vw,6.4rem)] font-[650] leading-[.92] tracking-[-.065em] max-[1050px]:text-[3.2rem]">
                    Setiap catatan adalah awal dari
                    <em class="font-serif font-normal not-italic text-[#fbc02d]">perubahan.</em>
                </h1>
                <p class="max-w-[530px] text-[1.02rem] leading-[1.75] text-[#efd7be]">
                    Satukan pelaporan, pendampingan, dan apresiasi siswa dalam alur yang transparan untuk seluruh warga sekolah.
                </p>
            </div>
            
            <div class="relative z-[1] grid max-w-[610px] grid-cols-3 border-t border-white/[.09] pt-[1.2rem]">
                <div class="relative grid gap-1 before:absolute before:-top-[1.48rem] before:h-[7px] before:w-[7px] before:rounded-full before:bg-[#fbc02d] before:shadow-[0_0_0_4px_#f9a82522]">
                    <strong class="text-[1.6rem]">25</strong><span class="text-[.72rem] text-[#f4d1a8]">Pemantauan</span>
                </div>
                <div class="relative grid gap-1 before:absolute before:-top-[1.48rem] before:h-[7px] before:w-[7px] before:rounded-full before:bg-[#fbc02d] before:shadow-[0_0_0_4px_#f9a82522]">
                    <strong class="text-[1.6rem]">50</strong><span class="text-[.72rem] text-[#f4d1a8]">Panggilan orang tua</span>
                </div>
                <div class="relative grid gap-1 before:absolute before:-top-[1.48rem] before:h-[7px] before:w-[7px] before:rounded-full before:bg-[#fbc02d] before:shadow-[0_0_0_4px_#f9a82522]">
                    <strong class="text-[1.6rem]">100</strong><span class="text-[.72rem] text-[#f4d1a8]">Penanganan khusus</span>
                </div>
            </div>
        </section>

        <!-- Section Form Login (Card Ringkas/Kompak di Mobile) -->
        <section class="w-full max-w-[380px] rounded-2xl bg-white p-5 shadow-lg shadow-slate-200/50 border border-slate-100 min-[761px]:max-w-none min-[761px]:rounded-none min-[761px]:border-none min-[761px]:bg-white min-[761px]:p-12 min-[761px]:shadow-none flex flex-col justify-center">
            <div class="w-full mx-auto max-w-[380px] min-[761px]:max-w-[420px]">
                <div class="mb-4 flex flex-col items-center justify-center gap-1.5 border-b border-slate-100 pb-3 min-[761px]:hidden">
                    <img src="{{ asset('Logo_SMAN_1_Tasikmalaya.png') }}" alt="Logo SMAN 1 Tasikmalaya" class="block h-[48px] w-auto">
                    <div class="text-center">
                        <strong class="block text-[1rem] font-bold tracking-[.03em] text-[#6d1a1a]">POINTKU</strong>
                        <small class="block text-[.6rem] text-[#8c6d6d]">Student Care System</small>
                    </div>
                </div>

                <p class="mb-1 text-[.65rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">PORTAL SEKOLAH</p>
                <h2 class="my-0.5 text-[1.5rem] font-bold leading-[1.15] tracking-[-.04em] min-[761px]:text-[2rem]">Selamat datang kembali</h2>
                <p class="mb-4 text-[.85rem] leading-[1.5] text-[#8c6d6d] min-[761px]:text-[1rem] min-[761px]:mb-[2.2rem]">
                    Masuk menggunakan akun yang telah diberikan sekolah.
                </p>

                <form class="login-form" method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <label class="my-3 grid gap-[.4rem] text-[.75rem] font-bold text-[#5d4037]">
                        Username
                        <span class="flex items-center rounded-xl border border-slate-300 bg-slate-50 min-[761px]:bg-slate-100 transition focus-within:border-[#6d1a1a] focus-within:shadow-[0_0_0_3px_#6d1a1a14]">
                            <input class="min-w-0 flex-1 border-0 bg-transparent p-[.75rem_.9rem] text-[#4a1c1c] outline-none text-[.85rem]" name="username" value="{{ old('username') }}" required autofocus placeholder="contoh: guru.bk">
                        </span>
                    </label>

                    <label class="my-3 grid gap-[.4rem] text-[.75rem] font-bold text-[#5d4037]">
                        Password
                        <span class="flex items-center rounded-xl border border-slate-300 bg-slate-50 min-[761px]:bg-slate-100 transition focus-within:border-[#6d1a1a] focus-within:shadow-[0_0_0_3px_#6d1a1a14]">
                            <input class="min-w-0 flex-1 border-0 bg-transparent p-[.75rem_.9rem] text-[#4a1c1c] outline-none text-[.85rem]" id="password" name="password" required type="password" placeholder="Masukkan password">
                            <button id="password-toggle" class="grid place-items-center border-0 bg-transparent px-3 py-2 text-[#8d6e63]" type="button" aria-label="Tampilkan password" aria-pressed="false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="js-eye h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="js-eye-off hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c6.5 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3.5 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                    <line x1="2" x2="22" y1="2" y2="22"></line>
                                </svg>
                            </button>
                        </span>
                    </label>

                    <!-- Tombol Login (Teks "Masuk" Singkat & UI Modern) -->
                    <button id="login-btn" class="group relative mt-4 flex min-h-[44px] w-full items-center justify-center overflow-hidden rounded-xl border-0 bg-gradient-to-r from-[#6d1a1a] to-[#8b2323] px-5 py-2.5 text-[.85rem] font-semibold text-white shadow-md shadow-red-950/20 transition-all duration-200 hover:from-[#5a1515] hover:to-[#721c1c] hover:shadow-lg hover:shadow-red-950/30 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-75" type="submit">
                        <span id="login-text" class="flex items-center justify-center gap-2">
                            <span>Masuk</span>
                            <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                        <i id="login-spinner" data-lucide="loader-circle" class="hidden h-5 w-5 animate-spin"></i>
                    </button>

                    @error('username')
                        <p class="mt-2 text-[.75rem] text-[#c62828]">
                            {{ $message }}
                        </p>
                    @enderror
                </form>

                <p class="mt-4 text-center text-[.68rem] text-[#a1887f]">
                    Kesulitan masuk? Hubungi administrator sekolah.
                </p>
            </div>
        </section>
    </main>

    <script>
        const password = document.getElementById('password');
        const toggle = document.getElementById('password-toggle');
        
        toggle.addEventListener('click', () => {
            const show = password.type === 'password';
            password.type = show ? 'text' : 'password';
            toggle.querySelector('.js-eye').classList.toggle('hidden', show);
            toggle.querySelector('.js-eye-off').classList.toggle('hidden', !show);
            toggle.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            toggle.setAttribute('aria-pressed', String(show));
        });

        const loginForm = document.querySelector('.login-form');
        const loginBtn = document.getElementById('login-btn');

        loginForm.addEventListener('submit', () => {
            loginBtn.disabled = true;
            document.getElementById('login-text')?.classList.add('hidden');
            document.getElementById('login-spinner')?.classList.remove('hidden');
        });
    </script>
</body>
</html>