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
<body>
    <main class="grid min-h-screen grid-cols-1 bg-white min-[761px]:grid-cols-[minmax(420px,1.08fr)_minmax(420px,.92fr)]">
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

        <section class="flex flex-1 items-center justify-center bg-[linear-gradient(150deg,#fff,#fff8e1)] p-6 min-[461px]:p-12 min-[761px]:grid min-[761px]:p-12">
            <div class="w-full max-w-[420px]">
                <div class="mb-6 flex items-center gap-[.8rem] border-b border-[#fce4c4] pb-4 min-[761px]:hidden">
                    <img src="{{ asset('Logo_SMAN_1_Tasikmalaya.png') }}" alt="Logo SMAN 1 Tasikmalaya" class="block h-[38px] w-auto">
                    <div><strong class="block text-[1.1rem] font-bold tracking-[.03em] text-[#6d1a1a]">POINTKU</strong><small class="mt-1 block text-[.65rem] text-[#8c6d6d]">Student Care System</small>
                    </div>
                </div>

                <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">PORTAL SEKOLAH</p>
                <h2 class="my-1 text-[1.85rem] font-bold leading-[1.15] tracking-[-.05em] min-[761px]:text-[2.6rem]">Selamat datang kembali</h2>
                <p class="mb-6 leading-[1.6] text-[#8c6d6d] min-[761px]:mb-[2.2rem]">
                    Masuk menggunakan akun yang telah diberikan sekolah.
                </p>

                <form class="login-form" method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <label class="my-4 grid gap-[.55rem] text-[.78rem] font-bold text-[#5d4037]">
                        Username
                        <span class="flex items-center rounded-xl border border-[#fce4c4] bg-white transition focus-within:border-[#6d1a1a] focus-within:shadow-[0_0_0_4px_#6d1a1a14]">
                            <input class="min-w-0 flex-1 border-0 bg-transparent p-[.9rem_1rem] text-[#4a1c1c] outline-none" name="username" value="{{ old('username') }}" required autofocus placeholder="contoh: guru.bk">
                        </span>
                    </label>

                    <label class="my-4 grid gap-[.55rem] text-[.78rem] font-bold text-[#5d4037]">
                        Password
                        <span class="flex items-center rounded-xl border border-[#fce4c4] bg-white transition focus-within:border-[#6d1a1a] focus-within:shadow-[0_0_0_4px_#6d1a1a14]">
                            <input class="min-w-0 flex-1 border-0 bg-transparent p-[.9rem_1rem] text-[#4a1c1c] outline-none" id="password" name="password" required type="password" placeholder="Masukkan password">
                            <button id="password-toggle" class="grid place-items-center border-0 bg-transparent px-4 py-3 text-[#8d6e63]" type="button" aria-label="Tampilkan password" aria-pressed="false">
                                Lihat
                            </button>
                        </span>
                    </label>

                    <button id="login-btn" class="mt-5 flex min-h-[48px] w-full items-center justify-center rounded-[10px] border-0 bg-[#6d1a1a] px-5 py-3 text-[.8rem] font-bold text-white shadow-[0_8px_20px_#6d1a1a38] transition hover:-translate-y-px hover:bg-[#5a1515] disabled:cursor-not-allowed disabled:opacity-80" type="submit">
                        <span id="login-text" class="flex w-full items-center justify-between">
                            <span>Masuk ke dashboard</span>
                            <span>→</span>
                        </span>
                        <i id="login-spinner" data-lucide="loader-circle" class="hidden h-5 w-5 animate-spin"></i>
                    </button>

                    @error('username')
                        <p class="mt-2 min-h-[1.2rem] text-[.75rem] text-[#c62828]">
                            {{ $message }}
                        </p>
                    @enderror
                </form>

                <p class="mt-8 text-center text-[.7rem] text-[#a1887f]">
                    Kesulitan masuk? Hubungi administrator sekolah.
                </p>
            </div>
        </section>
    </main>

    <script>
        const password = document.getElementById('password');
        const toggle = document.getElementById('password-toggle');
        
        toggle.addEventListener('click', () => {
            const visible = password.type === 'text';
            password.type = visible ? 'password' : 'text';
            toggle.textContent = visible ? 'Lihat' : 'Sembunyikan';
            toggle.setAttribute('aria-label', visible ? 'Tampilkan password' : 'Sembunyikan password');
            toggle.setAttribute('aria-pressed', String(!visible));
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
