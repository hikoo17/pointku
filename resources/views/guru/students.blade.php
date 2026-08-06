@php($title = 'Rekap Siswa')

<x-layouts.app :title="$title">
    <x-dashboard
        title="Rekap perkembangan siswa"
        eyebrow="PEMANTAUAN BK"
        copy="Cari siswa yang membutuhkan pendampingan berdasarkan saldo dan total pelanggaran."
    />

    {{-- Student Table --}}
    <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">Kelas</th>
                        <th class="px-5 py-3 text-right">Pelanggaran</th>
                        <th class="px-5 py-3 text-right">Apresiasi</th>
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
                            <td class="px-5 py-3.5 text-right font-bold text-rose-600">{{ $student->total_poin_pelanggaran }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-emerald-600">+{{ $student->total_poin_apresiasi }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-900">{{ $student->saldo_poin }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[0.68rem] font-bold {{ $student->total_poin_pelanggaran >= 25 ? 'text-rose-700 bg-rose-50 border border-rose-100' : 'text-slate-600 bg-slate-100 border border-slate-200/60' }}">
                                    {{ $student->total_poin_pelanggaran >= 25 ? 'Perlu ditindaklanjuti' : 'Normal' }}
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
