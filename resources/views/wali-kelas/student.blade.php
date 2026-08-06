@php($title = 'Detail Siswa')

<x-layouts.app :title="$title">
    <div class="mb-5">
        <a class="group inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900" href="{{ route('wali-kelas.students') }}">
            <i data-lucide="arrow-left" class="h-3.5 w-3.5 fill-none stroke-current transition-transform duration-200 group-hover:-translate-x-0.5"></i>
            Kembali ke daftar siswa
        </a>
    </div>

    <x-dashboard
        title="{{ $siswa->user->nama_lengkap }}"
        eyebrow="DETAIL SISWA"
        copy="Ringkasan poin, riwayat catatan, dan peringatan untuk NISN {{ $siswa->nisn }} kelas {{ $siswa->kelas->nama_kelas }}."
    />

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-2 gap-4 min-[1051px]:grid-cols-4">
        @foreach([
            ['Pelanggaran', $siswa->total_poin_pelanggaran, 'circle-alert', 'text-rose-700', 'bg-rose-50 border-rose-100', 'border-t-rose-600'],
            ['Apresiasi', $siswa->total_poin_apresiasi, 'heart', 'text-amber-700', 'bg-amber-50 border-amber-100', 'border-t-amber-500'],
            ['Saldo Poin', $siswa->saldo_poin, 'scale', 'text-slate-700', 'bg-slate-100 border-slate-200/60', 'border-t-slate-400'],
            ['Status', $siswa->total_poin_pelanggaran >= 25 ? 'Dipantau' : 'Normal', 'shield', $siswa->total_poin_pelanggaran >= 25 ? 'text-rose-700' : 'text-emerald-700', $siswa->total_poin_pelanggaran >= 25 ? 'bg-rose-50 border-rose-100' : 'bg-emerald-50 border-emerald-100', $siswa->total_poin_pelanggaran >= 25 ? 'border-t-rose-600' : 'border-t-emerald-600'],
        ] as [$label, $value, $icon, $textColor, $badgeBg, $borderTop])
            <article class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200/80 border-t-[3px] {{ $borderTop }} bg-white p-4.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md min-[761px]:p-5">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">{{ $label }}</span>
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border {{ $badgeBg }} {{ $textColor }}">
                        <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <strong class="block text-3xl font-bold tracking-tight text-slate-900">{{ $value }}</strong>
                </div>
            </article>
        @endforeach
    </div>

    {{-- Records --}}
    <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
            <div>
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">RIWAYAT POIN</span>
                <h3 class="text-base font-bold text-slate-900">Catatan tervalidasi</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Jenis</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Pencatat</th>
                        <th class="px-5 py-3 text-right">Poin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($records as $record)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-5 py-3.5 font-medium text-slate-700">{{ $record->kategoriPoin->nama_kategori }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[0.68rem] font-semibold capitalize {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-rose-50 text-rose-700 border border-rose-200/60' }}">
                                    {{ ucfirst($record->kategoriPoin->jenis) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-slate-600">{{ $record->tanggal->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5 font-medium text-slate-600">{{ $record->pencatat->nama_lengkap }}</td>
                            <td class="px-5 py-3.5 text-right font-bold {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $record->kategoriPoin->jenis === 'apresiasi' ? '+' : '-' }}{{ $record->kategoriPoin->bobot_poin }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <span class="text-xs font-medium">Belum ada riwayat tervalidasi.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
            <div class="border-t border-slate-100 px-5 py-3 bg-slate-50/50">
                {{ $records->links() }}
            </div>
        @endif
    </section>

    {{-- Peringatan --}}
    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
            <div>
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">PERINGATAN</span>
                <h3 class="text-base font-bold text-slate-900">Status peringatan</h3>
            </div>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($alerts as $alert)
                <div class="flex items-start gap-3 px-5 py-4 {{ $alert->is_resolved ? 'opacity-65' : '' }}">
                    <span class="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-lg border {{ $alert->level === 'berat' ? 'bg-rose-50 text-rose-600 border-rose-100' : ($alert->level === 'sedang' ? 'bg-orange-50 text-orange-500 border-orange-100' : 'bg-blue-50 text-blue-500 border-blue-100') }}">
                        <i data-lucide="{{ $alert->level === 'berat' ? 'circle-alert' : ($alert->level === 'sedang' ? 'triangle-alert' : 'info') }}" class="h-4 w-4"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <strong class="flex items-center gap-2 text-xs font-bold text-slate-800">
                            {{ $alert->judul }}
                            @if($alert->is_resolved)
                                <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[0.65rem] font-semibold text-emerald-700 border border-emerald-100">Selesai</span>
                            @else
                                <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[0.65rem] font-semibold text-amber-700 border border-amber-100">Dipantau</span>
                            @endif
                        </strong>
                        <p class="mt-1 text-[0.78rem] leading-relaxed text-slate-500">{{ $alert->pesan }}</p>
                    </div>
                </div>
            @empty
                <div class="grid min-h-36 place-items-center px-5 py-8 text-center">
                    <div>
                        <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-full bg-emerald-50 text-emerald-600">
                            <i data-lucide="shield-check" class="h-5 w-5"></i>
                        </span>
                        <strong class="block text-xs font-bold text-slate-700">Tidak ada peringatan</strong>
                        <p class="mt-1 text-[0.68rem] font-medium text-slate-400">Siswa ini belum mencapai threshold peringatan.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</x-layouts.app>
