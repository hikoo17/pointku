@php($title = 'Detail Kelas')

<x-layouts.app :title="$title">
    <x-dashboard
        title="Kelas {{ $kelas->nama_kelas }}"
        eyebrow="RINGKASAN KELAS"
        copy="Tinjau daftar siswa, jumlah kejadian, dan total poin pelanggaran maupun apresiasi."
    />

    {{-- Back Button --}}
    <div class="mb-5">
        <a class="group inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900" href="{{ route('kesiswaan.master.classes') }}">
            <i data-lucide="arrow-left" class="h-3.5 w-3.5 fill-none stroke-current transition-transform duration-200 group-hover:-translate-x-0.5"></i>
            Kembali ke daftar kelas
        </a>
    </div>

    {{-- Summary Grid --}}
    <div class="mb-6 grid grid-cols-2 gap-4 min-[1051px]:grid-cols-4">
        @foreach([
            ['Siswa', 'students', 'users', 'text-sky-700', 'bg-sky-50 border-sky-100', 'border-t-sky-600', 'Siswa terdaftar'],
            ['Pelanggaran', 'violations', 'circle-alert', 'text-rose-700', 'bg-rose-50 border-rose-100', 'border-t-rose-600', 'Kejadian disetujui'],
            ['Apresiasi', 'appreciations', 'heart', 'text-emerald-700', 'bg-emerald-50 border-emerald-100', 'border-t-emerald-600', 'Kejadian disetujui'],
            ['Perlu Dipantau', 'attention', 'triangle-alert', 'text-orange-700', 'bg-orange-50 border-orange-100', 'border-t-orange-500', 'Minimal 25 poin'],
        ] as [$label, $key, $icon, $textColor, $badgeBg, $borderTop, $caption])
            <article class="flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200/80 border-t-[3px] {{ $borderTop }} bg-white p-4.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md min-[761px]:p-5">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">{{ $label }}</span>
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border {{ $badgeBg }} {{ $textColor }}">
                        <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <strong class="block text-3xl font-bold tracking-tight text-slate-900">{{ $summary[$key] }}</strong>
                    <small class="mt-0.5 block text-[0.65rem] font-medium text-slate-400">{{ $caption }}</small>
                </div>
            </article>
        @endforeach
    </div>

    {{-- Student Table --}}
    <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3 text-right">Pelanggaran</th>
                        <th class="px-5 py-3 text-right">Apresiasi</th>
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
                            <td class="px-5 py-3.5 text-right font-bold text-rose-600">{{ $student->total_poin_pelanggaran }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-emerald-600">+{{ $student->total_poin_apresiasi }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[0.68rem] font-bold {{ $student->total_poin_pelanggaran >= 25 ? 'text-rose-700 bg-rose-50 border border-rose-100' : 'text-slate-600 bg-slate-100 border border-slate-200/60' }}">
                                    {{ $student->total_poin_pelanggaran >= 25 ? 'Perlu dipantau' : 'Normal' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                <span class="text-xs font-medium">Belum ada siswa di kelas ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
