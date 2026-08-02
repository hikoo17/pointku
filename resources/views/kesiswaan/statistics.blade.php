@php($title='Statistik Sekolah')
@php($navigation=[['kesiswaan.dashboard','Ringkasan','dashboard'],['kesiswaan.reports','Laporan masuk','file'],['kesiswaan.statistics','Statistik sekolah','chart'],['kesiswaan.letters','Surat panggilan','letter']])

<x-layouts.app :title="$title" :navigation="$navigation">
    <x-dashboard
        title="Insight sekolah"
        eyebrow="DATA UNTUK PENDAMPINGAN"
        copy="Baca pola poin berdasarkan kelas untuk menentukan intervensi."
    />

    <div class="mb-[1.2rem] grid grid-cols-2 gap-[0.7rem] min-[761px]:gap-4 min-[1051px]:grid-cols-4">
        @foreach([
            ['Pelanggaran', 'violations', 'alert', 'text-[#b71c1c] border-t-[3px] border-t-[#b71c1c]'],
            ['Apresiasi', 'appreciations', 'heart', 'text-[#f9a825] border-t-[3px] border-t-[#fbc02d66]'],
            ['Pending', 'pending', 'clock', 'text-[#f57f17] border-t-[3px] border-t-[#fbc02d]'],
            ['Perlu penanganan', 'attention', 'users', 'text-[#6d1a1a] border-t-[3px] border-t-[#6d1a1a]'],
        ] as [$label,$key,$icon,$theme])
            <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] {{ $theme }}">
                <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                    <svg>
                        <use href="#icon-{{ $icon }}"></use>
                    </svg>
                </span>
                <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">{{ $label }}</span>
                <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $stats[$key] }}</strong>
                <small class="text-[.62rem] text-[#8c6d6d]">Data terbaru</small>
            </article>
        @endforeach
    </div>

    <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr>
                            <td class="px-[1.5rem] py-[1rem] text-[.75rem] font-bold text-[#4a1c1c] border-t border-[#fff3e0]"><strong>{{ $class->nama_kelas }}</strong></td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">{{ $class->total_siswa }}</td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#c62828] border-t border-[#fff3e0]">{{ $class->pelanggaran }}</td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">+{{ $class->apresiasi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
