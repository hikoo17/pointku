@php($title='Dashboard Kesiswaan')
@php($navigation=[['kesiswaan.dashboard','Statistik sekolah','dashboard'],['kesiswaan.reports','Laporan masuk','file'],['kesiswaan.letters','Surat panggilan','letter']])

<x-layouts.app :title="$title" :navigation="$navigation">
    <x-dashboard
        title="Pantau seluruh sekolah"
        eyebrow="RUANG KESISWAAN"
        copy="Tinjau laporan, threshold, dan keputusan tindak lanjut siswa."
    />

    <div class="mb-[1.2rem] grid grid-cols-2 gap-[0.7rem] min-[761px]:gap-4 min-[1051px]:grid-cols-4">
        @foreach([
            ['Pelanggaran', 'violations', 'alert', 'text-[#b71c1c] border-t-[3px] border-t-[#b71c1c]'],
            ['Apresiasi', 'appreciations', 'heart', 'text-[#f9a825] border-t-[3px] border-t-[#fbc02d66]'],
            ['Laporan pending', 'pending', 'clock', 'text-[#f57f17] border-t-[3px] border-t-[#fbc02d]'],
            ['Perlu penanganan', 'attention', 'users', 'text-[#6d1a1a] border-t-[3px] border-t-[#6d1a1a]'],
        ] as [$label,$key,$icon,$theme])
            <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] {{ $theme }}">
                <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] max-[460px]:opacity-55 min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                    <svg class="text-white">
                        <use href="#icon-{{ $icon }}"></use>
                    </svg>
                </span>
                <span class="block text-[.68rem] font-[750] text-[#8c6d6d] max-[460px]:max-w-[85px]">{{ $label }}</span>
                <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $stats[$key] }}</strong>
                <small class="text-[.62rem] text-[#8c6d6d]">Data terbaru</small>
            </article>
        @endforeach
    </div>

    <div class="mt-[1.2rem] grid grid-cols-1 gap-[1.2rem] min-[761px]:grid-cols-[1.05fr_.95fr]">
        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
                <div>
                    <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">LAPORAN</p>
                    <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Laporan terbaru</h3>
                </div>
                <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a]" href="{{ route('kesiswaan.reports') }}">
                    Lihat semua
                    <svg>
                        <use href="#icon-arrow-right"></use>
                    </svg>
                </a>
            </div>

            @forelse($reports as $report)
                <a class="mx-6 flex items-center gap-[.8rem] border-b border-[#fce4c4] py-4" href="{{ route('kesiswaan.reports') }}">
                    <span class="grid h-[30px] w-[30px] place-items-center rounded-full bg-[#ffebee] text-[#b71c1c]">
                        <svg>
                            <use href="#icon-user"></use>
                        </svg>
                    </span>
                    <div>
                        <strong class="block text-[.74rem]">{{ $report->siswa->user->nama_lengkap }}</strong>
                        <small class="mt-[.2rem] block text-[.63rem] text-[#8d6e63]">{{ $report->jenis_tindakan }} · {{ $report->status }}</small>
                    </div>
                </a>
            @empty
                <p class="p-6 text-center text-[#8c6d6d]">Belum ada laporan.</p>
            @endforelse
        </section>

        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
                <div>
                    <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">THRESHOLD</p>
                    <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Perlu perhatian</h3>
                </div>
            </div>

            @forelse($students as $student)
                <a class="mx-6 flex items-center gap-[.8rem] border-b border-[#fce4c4] py-4" href="{{ route('kesiswaan.dashboard') }}">
                    <span class="inline-block h-[10px] w-[10px] rounded-full bg-[#b71c1c]"></span>
                    <div>
                        <strong class="block text-[.74rem]">{{ $student->user->nama_lengkap }}</strong>
                        <small class="mt-[.2rem] block text-[.63rem] text-[#8d6e63]">{{ $student->kelas->nama_kelas ?? '-' }} · {{ $student->total_poin_pelanggaran }} poin</small>
                    </div>
                </a>
            @empty
                <p class="p-6 text-center text-[#8c6d6d]">Tidak ada siswa melewati threshold.</p>
            @endforelse
        </section>
    </div>

    <section class="mt-[1.2rem] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
        <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
            <div>
                <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">KELAS</p>
                <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Ringkasan kelas</h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-separate min-w-[760px]">
                <thead>
                    <tr>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Kelas</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Siswa</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Pelanggaran</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Apresiasi</th>
                        <th class="px-[1.5rem] py-[.75rem] text-center text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr class="transition-colors hover:bg-[#fffdf5]">
                            <td class="px-[1.5rem] py-[1rem] text-[.75rem] font-bold text-[#4a1c1c] border-t border-[#fff3e0]"><strong>{{ $class->nama_kelas }}</strong></td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">{{ $class->total_siswa }}</td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#c62828] border-t border-[#fff3e0]">{{ $class->pelanggaran }}</td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">+{{ $class->apresiasi }}</td>
                            <td class="px-[1.5rem] py-[1rem] text-center border-t border-[#fff3e0]">
                                <a class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border border-[#fce4c4] bg-white px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-[#5d4037] shadow-[0_5px_18px_rgba(74,28,28,.03)] transition hover:bg-[#fff8e1]" href="{{ route('kesiswaan.classes.show', $class->id) }}">
                                    Detail
                                    <svg class="h-3.5 w-3.5"><use href="#icon-arrow-right"></use></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
