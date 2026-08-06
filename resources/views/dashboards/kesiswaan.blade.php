@php
    $title = 'Dashboard Kesiswaan';
    $classChartData = $classes->map(fn ($class) => [
        'label' => $class->nama_kelas,
        'violations' => (int) $class->pelanggaran,
        'appreciations' => (int) $class->apresiasi,
    ])->values();
@endphp

<x-layouts.app :title="$title">
    <x-dashboard
        title="Pantau seluruh sekolah"
        eyebrow="RUANG KESISWAAN"
        copy="Tinjau laporan, threshold, dan keputusan tindak lanjut siswa."
    />

    {{-- Stats Grid --}}
    <div class="mb-6 grid grid-cols-2 gap-4 min-[1051px]:grid-cols-4">
        @foreach([
            ['Pelanggaran', 'violations', 'alert', 'text-rose-700', 'bg-rose-50 border-rose-100', 'border-t-rose-600', null],
            ['Apresiasi', 'appreciations', 'heart', 'text-amber-700', 'bg-amber-50 border-amber-100', 'border-t-amber-500', null],
            ['Laporan Pending', 'pending', 'clock', 'text-orange-700', 'bg-orange-50 border-orange-100', 'border-t-orange-500', route('kesiswaan.reports', ['status' => 'pending'])],
            ['Perlu Penanganan', 'attention', 'users', 'text-[#5c1919]', 'bg-[#5c1919]/5 border-[#5c1919]/10', 'border-t-[#5c1919]', null],
        ] as [$label, $key, $icon, $textColor, $badgeBg, $borderTop, $href])
            <article class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200/80 border-t-[3px] {{ $borderTop }} bg-white p-4.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md min-[761px]:p-5">
                @if($href)
                    <a class="absolute inset-0 z-10" href="{{ $href }}" aria-label="Lihat {{ strtolower($label) }}"></a>
                @endif
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">{{ $label }}</span>
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border {{ $badgeBg }} {{ $textColor }}">
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                            <use href="#icon-{{ $icon }}"></use>
                        </svg>
                    </span>
                </div>
                <div class="mt-3">
                    <strong class="block text-3xl font-bold tracking-tight text-slate-900">{{ $stats[$key] }}</strong>
                    <small class="mt-0.5 flex items-center gap-1 text-[0.65rem] font-medium text-slate-400">
                        {{ in_array($key, ['violations', 'appreciations']) ? 'Kejadian disetujui' : 'Akumulasi saat ini' }}
                        @if($href)
                            <span class="font-bold text-slate-500">Lihat detail</span>
                        @endif
                    </small>
                </div>
            </article>
        @endforeach
    </div>

    {{-- Grafik Kejadian per Kelas --}}
    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
            <div>
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">ANALITIK</span>
                <h3 class="text-base font-bold text-slate-900">Kejadian per Kelas</h3>
                <p class="mt-1 text-xs font-medium text-slate-500">Perbandingan jumlah catatan pelanggaran dan apresiasi yang sudah disetujui.</p>
            </div>
            <a class="group hidden shrink-0 inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900 min-[761px]:inline-flex" href="{{ route('kesiswaan.master.classes') }}">
                Lihat semua kelas
                <svg class="h-3.5 w-3.5 fill-none stroke-current transition-transform duration-200 group-hover:translate-x-0.5" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

        <div class="overflow-x-auto p-4 min-[761px]:p-5">
            <div class="h-80" style="min-width: {{ max(640, $classes->count() * 90) }}px">
                <canvas
                    id="school-points-chart"
                    data-chart='@json($classChartData)'
                    aria-label="Grafik jumlah kejadian pelanggaran dan apresiasi per kelas"
                    role="img"
                ></canvas>
            </div>
        </div>
    </section>

    {{-- Content Grid --}}
    <div class="mt-6 grid grid-cols-1 gap-6 min-[761px]:grid-cols-2">
        {{-- Laporan Terbaru --}}
        <section class="flex flex-col overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">LAPORAN</span>
                    <h3 class="text-base font-bold text-slate-900">Laporan Terbaru</h3>
                </div>
                <a class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900" href="{{ route('kesiswaan.reports') }}">
                    Lihat semua
                    <svg class="h-3.5 w-3.5 fill-none stroke-current transition-transform duration-200 group-hover:translate-x-0.5" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($reports as $report)
                    <a class="flex items-center gap-3.5 px-5 py-3.5 transition hover:bg-slate-50/80" href="{{ route('kesiswaan.reports') }}">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-50 text-rose-700 border border-rose-100">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                <use href="#icon-user"></use>
                            </svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <strong class="block truncate text-xs font-bold text-slate-800">{{ $report->siswa->user->nama_lengkap }}</strong>
                            <small class="mt-0.5 block text-[0.68rem] font-medium text-slate-500">{{ $report->jenis_tindakan }} • <span class="font-semibold text-rose-600">{{ $report->status }}</span></small>
                        </div>
                    </a>
                @empty
                    <div class="grid min-h-36 place-items-center px-5 py-8 text-center">
                        <div>
                            <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-full bg-emerald-50 text-emerald-600">
                                <i data-lucide="check" class="h-5 w-5"></i>
                            </span>
                            <strong class="block text-xs font-bold text-slate-700">Belum ada laporan</strong>
                            <p class="mt-1 text-[0.68rem] font-medium text-slate-400">Laporan baru akan tampil di bagian ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Threshold / Perlu Perhatian --}}
        <section class="flex flex-col overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
            <div class="border-b border-slate-100 px-5 py-4">
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">THRESHOLD</span>
                <h3 class="text-base font-bold text-slate-900">Perlu Perhatian</h3>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($students as $student)
                    <a class="flex items-center gap-3.5 px-5 py-3.5 transition hover:bg-slate-50/80" href="{{ route('kesiswaan.dashboard') }}">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-rose-600 ring-4 ring-rose-50"></span>
                        <div class="min-w-0 flex-1">
                            <strong class="block truncate text-xs font-bold text-slate-800">{{ $student->user->nama_lengkap }}</strong>
                            <small class="mt-0.5 block text-[0.68rem] font-medium text-slate-500">{{ $student->kelas->nama_kelas ?? '-' }} • <span class="font-bold text-rose-600">{{ $student->total_poin_pelanggaran }} poin</span></small>
                        </div>
                    </a>
                @empty
                    <div class="grid min-h-36 place-items-center px-5 py-8 text-center">
                        <div>
                            <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-full bg-emerald-50 text-emerald-600">
                                <i data-lucide="shield-check" class="h-5 w-5"></i>
                            </span>
                            <strong class="block text-xs font-bold text-slate-700">Kondisi siswa terkendali</strong>
                            <p class="mt-1 text-[0.68rem] font-medium text-slate-400">Tidak ada siswa yang melewati threshold.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

</x-layouts.app>
