@php($title = 'Rekap Siswa')

<x-layouts.app :title="$title">
    <x-dashboard
        title="Rekap perkembangan siswa"
        eyebrow="PEMANTAUAN BK"
        copy="Cari siswa yang membutuhkan pendampingan berdasarkan saldo dan total pelanggaran."
    />

    <div class="mb-4 flex flex-col gap-3 rounded-xl border border-slate-200/80 bg-white p-4 shadow-xs min-[761px]:flex-row min-[761px]:items-end min-[761px]:justify-between">
        <form method="GET" action="{{ route('guru.students') }}" class="grid grid-cols-1 gap-3 min-[461px]:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto_auto] min-[461px]:items-end">
            <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                Dari tanggal
                <input type="date" name="dari" value="{{ request('dari') }}" class="h-9 rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-800 outline-none focus:border-slate-400">
            </label>
            <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                Sampai tanggal
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="h-9 rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-800 outline-none focus:border-slate-400">
            </label>
            <button type="submit" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-[#5c1919] px-4 text-xs font-semibold text-white transition hover:bg-[#4a1414]">
                <i data-lucide="filter" class="h-3.5 w-3.5"></i>
                Terapkan
            </button>
            @if(request()->hasAny(['dari', 'sampai']))
                <a href="{{ route('guru.students') }}" class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">Reset</a>
            @endif
        </form>
        <a href="{{ route('guru.students.export', request()->only(['dari', 'sampai'])) }}" class="inline-flex h-9 shrink-0 items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-4 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
            <i data-lucide="download" class="h-3.5 w-3.5"></i>
            Export CSV
        </a>
    </div>

    {{-- Student Table --}}
    <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">Kelas</th>
                        <th class="px-5 py-3 text-right">Poin Pelanggaran</th>
                        <th class="px-5 py-3 text-right">Poin Apresiasi</th>
                        <th class="px-5 py-3 text-right">Saldo</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($students as $student)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-[#6d1a1a] text-xs font-extrabold text-white">
                                        {{ substr($student->user->nama_lengkap, 0, 1) }}
                                    </span>
                                    <div class="min-w-0">
                                        <strong class="block truncate text-xs font-bold text-slate-800">{{ $student->user->nama_lengkap }}</strong>
                                        <small class="block truncate text-[0.68rem] font-medium text-slate-500">{{ $student->nisn }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-slate-600">{{ $student->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-rose-600">{{ (int) $student->periode_poin_pelanggaran }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-emerald-600">+{{ (int) $student->periode_poin_apresiasi }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-900">{{ (int) $student->periode_poin_apresiasi - (int) $student->periode_poin_pelanggaran }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[0.68rem] font-bold {{ (int) $student->periode_poin_pelanggaran >= 25 ? 'text-rose-700 bg-rose-50 border border-rose-100' : 'text-slate-600 bg-slate-100 border border-slate-200/60' }}">
                                    {{ (int) $student->periode_poin_pelanggaran >= 25 ? 'Perlu ditindaklanjuti' : 'Normal' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-xl bg-slate-50 text-slate-400">
                                    <i data-lucide="users" class="h-5 w-5"></i>
                                </span>
                                <span class="block text-xs font-medium">Belum ada data siswa.</span>
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
