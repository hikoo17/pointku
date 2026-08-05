@php($title = 'Detail Kelas')

<x-layouts.app :title="$title" >
    <x-dashboard
        title="Kelas {{ $kelas->nama_kelas }}"
        eyebrow="RINGKASAN KELAS"
        copy="Tinjau daftar siswa dan total poin pelanggaran maupun apresiasi."
    />

    <div class="mb-[1.2rem] grid grid-cols-2 gap-[0.7rem] min-[761px]:gap-4 min-[1051px]:grid-cols-4">
        @foreach([
            ['Jumlah siswa', 'students', 'users', 'text-[#6d1a1a] border-t-[3px] border-t-[#6d1a1a]'],
            ['Pelanggaran', 'violations', 'alert', 'text-[#b71c1c] border-t-[3px] border-t-[#b71c1c]'],
            ['Apresiasi', 'appreciations', 'heart', 'text-[#f9a825] border-t-[3px] border-t-[#fbc02d66]'],
            ['Perlu perhatian', 'attention', 'clock', 'text-[#f57f17] border-t-[3px] border-t-[#fbc02d]'],
        ] as [$label, $key, $icon, $theme])
            <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] {{ $theme }}">
                <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] max-[460px]:opacity-55 min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                    <svg class="text-white">
                        <use href="#icon-{{ $icon }}"></use>
                    </svg>
                </span>
                <span class="block text-[.68rem] font-[750] text-[#8c6d6d] max-[460px]:max-w-[85px]">{{ $label }}</span>
                <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $summary[$key] }}</strong>
            </article>
        @endforeach
    </div>

    <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
        <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
            <div>
                <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">SISWA</p>
                <h3 class="text-[1.08rem] font-bold tracking-[-.025em]">Daftar siswa kelas {{ $kelas->nama_kelas }}</h3>
            </div>
            <a class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border border-[#fce4c4] bg-white px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-[#5d4037] shadow-[0_5px_18px_rgba(74,28,28,.03)] transition hover:bg-[#fff8e1]" href="{{ route('kesiswaan.dashboard') }}">
                Kembali
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-separate min-w-[760px]">
                <thead>
                    <tr>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Siswa</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Pelanggaran</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Apresiasi</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <div class="flex items-center gap-[.7rem]">
                                    <span class="grid h-[31px] w-[31px] place-items-center rounded-[9px] bg-[#6d1a1a] font-[850] text-white">{{ substr($student->user->nama_lengkap,0,1) }}</span>
                                    <span>
                                        <strong class="block text-[.75rem] text-[#4a1c1c]">{{ $student->user->nama_lengkap }}</strong>
                                        <small class="mt-[.18rem] block text-[.61rem] text-[#a1887f]">{{ $student->nisn }}</small>
                                    </span>
                                </div>
                            </td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#c62828] border-t border-[#fff3e0]">{{ $student->total_poin_pelanggaran }}</td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">+{{ $student->total_poin_apresiasi }}</td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <span class="inline-flex items-center gap-[.35rem] rounded-[99px] px-[.55rem] py-[.3rem] text-[.58rem] font-extrabold capitalize {{ $student->total_poin_pelanggaran >= 25 ? 'text-[#bf360c] bg-[#ffe0b2]' : 'text-[#5d4037] bg-[#fff9c4]' }}">
                                    {{ $student->total_poin_pelanggaran >= 25 ? 'Perlu dipantau' : 'Normal' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="h-[180px] text-center text-[#a1887f] border-t border-[#fff3e0]">
                                <span class="mx-auto mb-[.7rem] grid h-[42px] w-[42px] place-items-center rounded-[12px] bg-[#ffebee] text-[#b71c1c]">
                                    <svg>
                                        <use href="#icon-users"></use>
                                    </svg>
                                </span>
                                Belum ada siswa di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
