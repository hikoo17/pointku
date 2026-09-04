<x-layouts.app title="Profile">
    <x-dashboard
        eyebrow="AKUN SAYA"
        title="Profile"
        copy="Kelola informasi akun yang digunakan untuk masuk ke POINTKU."
    />

    <div class="grid gap-5 min-[761px]:grid-cols-[minmax(0,1fr)_minmax(0,1.35fr)]">
        <article class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
            <div class="flex flex-col items-center text-center">
                <span class="grid h-24 w-24 place-items-center rounded-full bg-[#fbc02d] text-4xl font-extrabold text-[#4a1c1c]">
                    {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                </span>
                <h2 class="mt-4 text-xl font-bold text-slate-900">{{ $user->nama_lengkap }}</h2>
                <p class="mt-1 text-sm font-medium text-slate-500">{{ $user->role->nama_role }}</p>
                <button type="button" data-open="edit-profile" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-[#5c1919] px-4 py-2.5 text-xs font-bold text-white shadow-2xs transition hover:bg-[#4a1414]">
                    <i data-lucide="pencil" class="h-4 w-4"></i>
                    Edit profile
                </button>
            </div>
        </article>

        <article class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
            <h2 class="text-base font-bold text-slate-900">Informasi akun</h2>
            <dl class="mt-4 divide-y divide-slate-100">
                <div class="flex items-center justify-between gap-4 py-3">
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Nama lengkap</dt>
                    <dd class="text-right text-sm font-semibold text-slate-800">{{ $user->nama_lengkap }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 py-3">
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Username</dt>
                    <dd class="text-right text-sm font-semibold text-slate-800">{{ $user->username }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 py-3">
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Role</dt>
                    <dd class="rounded-md bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800">{{ $user->role->nama_role }}</dd>
                </div>
            </dl>
        </article>
    </div>

    <div id="edit-profile" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#4a1c1c]/40 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="edit-profile-title">
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[0.68rem] font-extrabold uppercase tracking-[.18em] text-[#6d1a1a]">PENGATURAN AKUN</p>
                    <h2 id="edit-profile-title" class="mt-1 text-xl font-bold text-slate-900">Edit profile</h2>
                </div>
                <button type="button" data-close="edit-profile" class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')
                @if ($errors->any())
                    <div class="rounded-lg border border-red-100 bg-red-50 p-3 text-xs font-medium text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif
                <label class="block text-xs font-bold text-slate-700">Nama lengkap
                    <input name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required class="mt-1.5 w-full rounded-lg border border-slate-200 bg-slate-50/50 p-3 text-sm text-slate-900 outline-none transition focus:border-[#6d1a1a] focus:bg-white focus:ring-2 focus:ring-[#6d1a1a]/10">
                </label>
                <label class="block text-xs font-bold text-slate-700">Username
                    <input name="username" value="{{ old('username', $user->username) }}" required class="mt-1.5 w-full rounded-lg border border-slate-200 bg-slate-50/50 p-3 text-sm text-slate-900 outline-none transition focus:border-[#6d1a1a] focus:bg-white focus:ring-2 focus:ring-[#6d1a1a]/10">
                </label>
                <div class="border-t border-slate-100 pt-4">
                    <p class="mb-3 text-xs font-bold text-slate-700">Ganti password <span class="font-normal text-slate-400">(opsional)</span></p>
                    <div class="grid gap-4 min-[500px]:grid-cols-2">
                        <input type="password" name="password" minlength="8" placeholder="Password baru" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-3 text-sm text-slate-900 outline-none transition focus:border-[#6d1a1a] focus:bg-white focus:ring-2 focus:ring-[#6d1a1a]/10">
                        <input type="password" name="password_confirmation" minlength="8" placeholder="Ulangi password" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-3 text-sm text-slate-900 outline-none transition focus:border-[#6d1a1a] focus:bg-white focus:ring-2 focus:ring-[#6d1a1a]/10">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-close="edit-profile" class="rounded-lg border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Batal</button>
                    <button type="submit" class="rounded-lg bg-[#5c1919] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#4a1414]">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const profileDialog = document.getElementById('edit-profile');
        document.querySelectorAll('[data-open="edit-profile"]').forEach((button) => button.addEventListener('click', () => {
            profileDialog.classList.remove('hidden');
            profileDialog.classList.add('flex');
        }));
        document.querySelectorAll('[data-close="edit-profile"]').forEach((button) => button.addEventListener('click', () => {
            profileDialog.classList.add('hidden');
            profileDialog.classList.remove('flex');
        }));
        profileDialog?.addEventListener('click', (event) => {
            if (event.target === profileDialog) profileDialog.querySelector('[data-close="edit-profile"]').click();
        });
        @if ($errors->any())
            profileDialog.classList.remove('hidden');
            profileDialog.classList.add('flex');
        @endif
    </script>
</x-layouts.app>
