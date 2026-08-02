@php($title='Surat Panggilan')
@php($navigation=[['guru.dashboard','Ringkasan','dashboard'],['guru.records','Catatan poin','note'],['guru.students','Rekap siswa','users'],['guru.reports','Laporan kesiswaan','file'],['guru.letters','Surat panggilan','letter']])

<x-layouts.app :title="$title" :navigation="$navigation">
    <x-dashboard
        title="Draf surat panggilan"
        eyebrow="TINDAK LANJUT BK"
        copy="Pantau surat yang dibuat otomatis setelah siswa melewati threshold."
    />

    <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-separate min-w-[760px]">
                <thead>
                    <tr>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Nomor surat</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Siswa</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Threshold</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Status</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Total poin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($letters as $letter)
                        <tr>
                            <td class="px-[1.5rem] py-[1rem] text-[.75rem] font-bold text-[#4a1c1c] border-t border-[#fff3e0]"><strong>{{ $letter->nomor_surat }}</strong></td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <div class="flex items-center gap-[.7rem]">
                                    <span class="grid h-[31px] w-[31px] place-items-center rounded-[9px] bg-[#6d1a1a] font-[850] text-white">{{ substr($letter->siswa->user->nama_lengkap,0,1) }}</span>
                                    <span>{{ $letter->siswa->user->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">{{ $letter->aturanThreshold->level ?? '-' }}</td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <span class="inline-flex items-center gap-[.35rem] rounded-[99px] px-[.55rem] py-[.3rem] text-[.58rem] font-extrabold capitalize text-[#6d4c41] bg-[#f5e6d3]">{{ $letter->status }}</span>
                            </td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">{{ $letter->total_poin }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="h-[180px] text-center text-[#a1887f] border-t border-[#fff3e0]">
                                <span class="mx-auto mb-[.7rem] grid h-[42px] w-[42px] place-items-center rounded-[12px] bg-[#ffebee] text-[#b71c1c]">
                                    <svg>
                                        <use href="#icon-letter"></use>
                                    </svg>
                                </span>
                                Belum ada surat panggilan otomatis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $letters->links() }}
    </section>
</x-layouts.app>
