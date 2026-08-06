@php($title = 'Daftar Siswa')

<x-layouts.app :title="$title">
    <div class="mb-6">
        <x-dashboard
            title="Siswa kelas {{ $kelas->nama_kelas }}"
            eyebrow="MONITORING KELAS"
            copy="Cari siswa dan buka detail perkembangan poin yang telah divalidasi."
        />
    </div>

    {{-- Toolbar --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <form class="flex w-full max-w-md gap-2" method="GET">
            <input class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 text-xs text-slate-800 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NISN...">
            <select name="status" class="h-9 rounded-lg border border-slate-200 bg-slate-50/50 px-3 text-xs text-slate-800 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                <option value="">Semua status</option>
                <option value="normal" @selected(request('status') === 'normal')>Normal</option>
                <option value="dipantau" @selected(request('status') === 'dipantau')>Perlu dipantau</option>
            </select>
            <button class="inline-flex h-9 shrink-0 items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900" type="submit">
                <i data-lucide="search" class="h-3.5 w-3.5 fill-none stroke-current"></i>
                Cari
            </button>
        </form>
    </div>

    <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3 text-right">Pelanggaran</th>
                        <th class="px-5 py-3 text-right">Apresiasi</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($students as $student)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-[#6d1a1a] text-xs font-extrabold text-white">{{ substr($student->user->nama_lengkap, 0, 1) }}</span>
                                    <div class="min-w-0">
                                        <strong class="block truncate text-xs font-bold text-slate-800">{{ $student->user->nama_lengkap }}</strong>
                                        <small class="block truncate text-[0.68rem] font-medium text-slate-500">{{ $student->nisn }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-rose-600">{{ $student->total_poin_pelanggaran }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-emerald-600">+{{ $student->total_poin_apresiasi }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[0.68rem] font-bold {{ $student->total_poin_pelanggaran >= 25 ? 'text-rose-700 bg-rose-50 border border-rose-100' : 'text-slate-600 bg-slate-100 border border-slate-200/60' }}">
                                    {{ $student->total_poin_pelanggaran >= 25 ? 'Perlu dipantau' : 'Normal' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900" href="{{ route('wali-kelas.students.show', $student) }}">
                                    <i data-lucide="eye" class="h-3.5 w-3.5 fill-none stroke-current"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <span class="text-xs font-medium">Siswa tidak ditemukan.</span>
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
</x-layouts.app>
