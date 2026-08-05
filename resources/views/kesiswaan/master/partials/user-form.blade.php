<dialog id="{{ $dialogId }}" class="fixed inset-0 m-auto w-[min(92vw,520px)] overflow-hidden rounded-xl border border-slate-200/80 bg-white p-0 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm open:animate-in open:fade-in-0 open:zoom-in-95 backdrop:open:animate-in backdrop:open:fade-in-0 transition-all">
    <form method="POST" action="{{ $action }}">
        @csrf 
        @if($editing) @method('PUT') @endif

        {{-- Header Modal --}}
        <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/80 px-6 py-4">
            <h2 class="text-sm font-bold text-slate-900">
                {{ $editing ? 'Edit Pengguna' : 'Tambah Pengguna' }}
            </h2>
            <button type="button" data-close class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-200/60 hover:text-slate-700">
                <svg class="h-4 w-4 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Form Content --}}
        <div class="grid gap-4 p-6 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 font-normal" name="nama_lengkap" required value="{{ old('nama_lengkap', $editing?->nama_lengkap) }}" placeholder="Masukkan nama lengkap">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Username</label>
                <input class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 font-normal" name="username" required value="{{ old('username', $editing?->username) }}" placeholder="Masukkan username">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Role</label>
                <select class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 font-normal" name="role_id" required>
                    <option value="" disabled @selected(!$editing)>Pilih Role...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id', $editing?->role_id) === $role->id)>
                            {{ $role->nama_role }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">
                    Password <span class="font-normal text-slate-400">{{ $editing ? '(Kosongkan jika tidak diubah)' : '' }}</span>
                </label>
                <input type="password" minlength="8" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 font-normal" name="password" {{ $editing ? '' : 'required' }} placeholder="••••••••">
            </div>
        </div>

        {{-- Footer Modal --}}
        <div class="flex justify-end gap-2 border-t border-slate-100 bg-slate-50/50 px-6 py-3.5">
            <button type="button" data-close class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900">
                Batal
            </button>
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-[#5c1919] px-4 py-2 text-xs font-bold text-white shadow-2xs transition hover:bg-[#4a1414]">
                <svg class="h-3.5 w-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Simpan
            </button>
        </div>
    </form>
</dialog>