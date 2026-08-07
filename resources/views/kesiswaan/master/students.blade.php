@php($title = 'Data Siswa')

<x-layouts.app :title="$title">
    {{-- Header Page --}}
    <div class="mb-6">
        <x-dashboard
            eyebrow="DATA MASTER"
            title="Data Siswa"
            copy="Form ini sekaligus membuat akun login dan profil siswa."
        />
    </div>

    {{-- Toolbar Section --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <form class="flex w-full max-w-sm gap-2" method="GET">
            <input 
                class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 text-xs text-slate-800 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" 
                name="q" 
                value="{{ request('q') }}" 
                placeholder="Cari nama, username, atau NISN..."
            >
            <button class="inline-flex h-9 shrink-0 items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900" type="submit">
                <svg class="h-3.5 w-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                Cari
            </button>
        </form>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('kesiswaan.master.students.export', request()->query()) }}" class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50">Ekspor CSV</a>
            <a href="{{ route('kesiswaan.master.students.template') }}" class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50">Template CSV</a>
            <button type="button" data-open="import-student" class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50">Import CSV</button>
            <button type="button" data-open="create-student" class="inline-flex h-9 items-center gap-1.5 rounded-lg bg-[#5c1919] px-4 text-xs font-bold text-white shadow-2xs transition hover:bg-[#4a1414]">
                <svg class="h-4 w-4 fill-none stroke-current" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Siswa
            </button>
        </div>
    </div>

    {{-- Table Section --}}
    <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">NISN</th>
                        <th class="px-5 py-3">Kelas</th>
                        <th class="px-5 py-3">L/P</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($students as $student)
                        <tr class="transition hover:bg-slate-50/80">
                            {{-- Siswa (Nama & Username) --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#5c1919] font-bold text-white shadow-2xs">
                                        {{ strtoupper(mb_substr($student->user->nama_lengkap ?? 'S', 0, 1)) }}
                                    </span>
                                    <div>
                                        <span class="block font-bold text-slate-900">
                                            {{ $student->user->nama_lengkap ?? '-' }}
                                        </span>
                                        <span class="block text-[0.68rem] text-slate-400 font-normal">
                                            {{ $student->user->username ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- NISN --}}
                            <td class="px-5 py-3.5 font-medium text-slate-600">
                                {{ $student->nisn }}
                            </td>

                            {{-- Kelas --}}
                            <td class="px-5 py-3.5 font-medium text-slate-700">
                                {{ $student->kelas->nama_kelas ?? '-' }}
                            </td>

                            {{-- Gender --}}
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[0.68rem] font-semibold border {{ $student->jenis_kelamin === 'L' ? 'bg-sky-50 text-sky-700 border-sky-200/60' : 'bg-pink-50 text-pink-700 border-pink-200/60' }}">
                                    {{ $student->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <button type="button" data-open="edit-student-{{ $student->id }}" class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900">
                                        <svg class="h-3.5 w-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('kesiswaan.master.students.destroy', $student) }}" class="inline" data-confirm="Akun dan profil siswa akan dihapus permanen." data-confirm-title="Hapus siswa?" data-confirm-button="Ya, hapus">
                                        @csrf 
                                        @method('DELETE')
                                        <button class="group inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100" type="submit">
                                            <svg class="h-3.5 w-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <span class="text-xs font-medium">Belum ada siswa.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="border-t border-slate-100 px-5 py-3 bg-slate-50/50">
                {{ $students->links() }}
            </div>
        @endif
    </section>

    {{-- Dialog Import --}}
    <dialog id="import-student" class="fixed inset-0 m-auto w-[min(92vw,480px)] rounded-xl border border-slate-200/80 bg-white p-6 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm">
        <form method="POST" action="{{ route('kesiswaan.master.students.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <h2 class="text-sm font-bold text-slate-900">Import Data Siswa</h2>
            <p class="text-xs text-slate-500">Gunakan template CSV. File Excel dapat disimpan sebagai CSV UTF-8 terlebih dahulu.</p>
            <input required type="file" name="file" accept=".csv,.txt,text/csv" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2 text-xs text-slate-700 outline-none">
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-close class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100">Batal</button>
                <button type="submit" class="rounded-lg bg-[#5c1919] px-4 py-2 text-xs font-bold text-white hover:bg-[#4a1414]">Impor</button>
            </div>
        </form>
    </dialog>

    {{-- Dialog Create Siswa --}}
    <dialog id="create-student" class="fixed inset-0 m-auto w-[min(92vw,600px)] overflow-hidden rounded-xl border border-slate-200/80 bg-white p-0 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm open:animate-in open:fade-in-0 open:zoom-in-95">
        <form method="POST" action="{{ route('kesiswaan.master.students.store') }}">
            @csrf
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/80 px-6 py-4">
                <h2 class="text-sm font-bold text-slate-900">Tambah Siswa</h2>
                <button type="button" data-close class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-200/60 hover:text-slate-700">
                    <svg class="h-4 w-4 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="grid gap-4 p-6 text-xs sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap</label>
                    <input required name="nama_lengkap" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" placeholder="Masukkan nama lengkap siswa">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Username</label>
                    <input required name="username" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" placeholder="Username">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Password</label>
                    <input type="password" minlength="8" required name="password" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" placeholder="••••••••">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">NISN</label>
                    <input required name="nisn" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" placeholder="Nomor NISN">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kelas</label>
                    <select required name="kelas_id" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                        <option value="" disabled selected>Pilih kelas...</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 border-t border-slate-100 bg-slate-50/50 px-6 py-3.5">
                <button type="button" data-close class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100">Batal</button>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-[#5c1919] px-4 py-2 text-xs font-bold text-white hover:bg-[#4a1414]">Simpan</button>
            </div>
        </form>
    </dialog>

    {{-- Dialog Edit Siswa --}}
    @foreach($students as $student)
        <dialog id="edit-student-{{ $student->id }}" class="fixed inset-0 m-auto w-[min(92vw,600px)] overflow-hidden rounded-xl border border-slate-200/80 bg-white p-0 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm open:animate-in open:fade-in-0 open:zoom-in-95">
            <form method="POST" action="{{ route('kesiswaan.master.students.update', $student) }}">
                @csrf 
                @method('PUT')

                <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/80 px-6 py-4">
                    <h2 class="text-sm font-bold text-slate-900">Edit Siswa</h2>
                    <button type="button" data-close class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-200/60 hover:text-slate-700">
                        <svg class="h-4 w-4 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="grid gap-4 p-6 text-xs sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap</label>
                        <input required name="nama_lengkap" value="{{ old('nama_lengkap', $student->user->nama_lengkap ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" placeholder="Masukkan nama lengkap siswa">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Username</label>
                        <input required name="username" value="{{ old('username', $student->user->username ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" placeholder="Username">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            Password <span class="font-normal text-slate-400">(Opsional)</span>
                        </label>
                        <input type="password" minlength="8" name="password" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" placeholder="••••••••">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NISN</label>
                        <input required name="nisn" value="{{ old('nisn', $student->nisn) }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" placeholder="Nomor NISN">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kelas</label>
                        <select required name="kelas_id" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('kelas_id', $student->kelas_id) == $class->id)>
                                    {{ $class->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                            <option value="L" @selected(old('jenis_kelamin', $student->jenis_kelamin) === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin', $student->jenis_kelamin) === 'P')>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-slate-100 bg-slate-50/50 px-6 py-3.5">
                    <button type="button" data-close class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-[#5c1919] px-4 py-2 text-xs font-bold text-white hover:bg-[#4a1414]">Simpan</button>
                </div>
            </form>
        </dialog>
    @endforeach

    @include('kesiswaan.master.partials.dialog-script')
</x-layouts.app>