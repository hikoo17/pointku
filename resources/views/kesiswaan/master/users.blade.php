@php($title = 'Data Pengguna')

<x-layouts.app :title="$title">
    {{-- Header Page Konsisten --}}
    <div class="mb-6">
        <x-dashboard
            eyebrow="DATA MASTER"
            title="Pengguna"
            copy="Kelola akun petugas dan wali kelas. Akun siswa dibuat dari menu Siswa."
        />
    </div>

    {{-- Toolbar Section: Search di Kiri & Tambah Pengguna di Kanan --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <form class="flex w-full max-w-sm gap-2" method="GET">
            <input class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 text-xs text-slate-800 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" name="q" value="{{ request('q') }}" placeholder="Cari nama atau username...">
            <button class="inline-flex h-9 shrink-0 items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900" type="submit">
                <svg class="h-3.5 w-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                Cari
            </button>
        </form>

        <button type="button" data-open="create-user" class="inline-flex h-9 items-center gap-1.5 rounded-lg bg-[#5c1919] px-4 text-xs font-bold text-white shadow-2xs transition hover:bg-[#4a1414]">
            <svg class="h-4 w-4 fill-none stroke-current" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Tambah Pengguna
        </button>
    </div>

    {{-- Table Section --}}
    <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3">Username</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($users as $user)
                        <tr class="transition hover:bg-slate-50/80">
                            {{-- Nama --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#5c1919] font-bold text-white shadow-2xs">
                                        {{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}
                                    </span>
                                    <span class="truncate font-bold text-slate-900">
                                        {{ $user->nama_lengkap }}
                                    </span>
                                </div>
                            </td>

                            {{-- Username --}}
                            <td class="px-5 py-3.5 font-medium text-slate-600">
                                {{ $user->username }}
                            </td>

                            {{-- Role --}}
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-[0.68rem] font-semibold text-amber-800 border border-amber-200/60">
                                    {{ $user->role->nama_role }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <button type="button" data-open="edit-user-{{ $user->id }}" class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900">
                                        <svg class="h-3.5 w-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('kesiswaan.master.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')" class="inline">
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
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                <span class="text-xs font-medium">Belum ada pengguna.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="border-t border-slate-100 px-5 py-3 bg-slate-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </section>

    {{-- Dialog Partials --}}
    @include('kesiswaan.master.partials.user-form', ['dialogId' => 'create-user', 'action' => route('kesiswaan.master.users.store'), 'editing' => null])
    
    @foreach($users as $user) 
        @include('kesiswaan.master.partials.user-form', ['dialogId' => 'edit-user-'.$user->id, 'action' => route('kesiswaan.master.users.update', $user), 'editing' => $user]) 
    @endforeach
    
    @include('kesiswaan.master.partials.dialog-script')
</x-layouts.app>