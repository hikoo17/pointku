@php($title='Rekap Siswa')
@php($navigation=[['guru.dashboard','Ringkasan','dashboard'],['guru.records','Catatan poin','note'],['guru.students','Rekap siswa','users'],['guru.reports','Laporan kesiswaan','file'],['guru.letters','Surat panggilan','letter']])

<x-layouts.app :title="$title" :navigation="$navigation">
    <x-dashboard
        title="Rekap perkembangan siswa"
        eyebrow="PEMANTAUAN BK"
        copy="Cari siswa yang membutuhkan pendampingan berdasarkan saldo dan total pelanggaran."
    />

    <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-separate min-w-[760px]">
                <thead>
                    <tr>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Siswa</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Kelas</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Pelanggaran</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Apresiasi</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Saldo</th>
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
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">{{ $student->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#c62828] border-t border-[#fff3e0]">{{ $student->total_poin_pelanggaran }}</td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">+{{ $student->total_poin_apresiasi }}</td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]"><strong>{{ $student->saldo_poin }}</strong></td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <span class="inline-flex items-center gap-[.35rem] rounded-[99px] px-[.55rem] py-[.3rem] text-[.58rem] font-extrabold capitalize text-[#bf360c] bg-[#ffe0b2]">
                                    {{ $student->total_poin_pelanggaran >= 25 ? 'Perlu ditindaklanjuti' : 'Normal' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="h-[180px] text-center text-[#a1887f] border-t border-[#fff3e0]">
                                <span class="mx-auto mb-[.7rem] grid h-[42px] w-[42px] place-items-center rounded-[12px] bg-[#ffebee] text-[#b71c1c]">
                                    <svg>
                                        <use href="#icon-users"></use>
                                    </svg>
                                </span>
                                Belum ada data siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $students->links() }}
    </section>
</x-layouts.app>
