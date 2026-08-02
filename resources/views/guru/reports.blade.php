@php($title='Laporan Kesiswaan')
@php($navigation=[['guru.dashboard','Ringkasan','dashboard'],['guru.records','Catatan poin','note'],['guru.students','Rekap siswa','users'],['guru.reports','Laporan kesiswaan','file'],['guru.letters','Surat panggilan','letter']])

<x-layouts.app :title="$title" :navigation="$navigation">
    <x-dashboard
        title="Kirim laporan penanganan"
        eyebrow="ALUR TINDAK LANJUT"
        copy="Ajukan siswa yang membutuhkan keputusan resmi Kesiswaan."
    />

    <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
        <form method="POST" action="{{ route('guru.reports.store') }}" class="p-[1.5rem]">
            @csrf
            <div class="grid grid-cols-1 gap-[1rem] min-[461px]:grid-cols-2">
                <label class="grid gap-[.55rem] text-[.78rem] font-bold text-[#5d4037]">
                    Siswa
                    <select name="siswa_id" required class="min-w-0 flex-1 rounded-[11px] border border-[#fce4c4] bg-white p-[.9rem_1rem] text-[#4a1c1c] outline-none">
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->user->nama_lengkap }} · {{ $student->total_poin_pelanggaran }} poin
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-[.55rem] text-[.78rem] font-bold text-[#5d4037]">
                    Jenis tindakan
                    <input name="jenis_tindakan" required placeholder="Contoh: Pemanggilan orang tua" class="min-w-0 flex-1 rounded-[11px] border border-[#fce4c4] bg-white p-[.9rem_1rem] text-[#4a1c1c] outline-none">
                </label>
            </div>

            <button class="mt-5 inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border-0 bg-[#6d1a1a] px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-white shadow-[0_8px_20px_#6d1a1a38] transition hover:-translate-y-px hover:bg-[#5a1515]" type="submit">
                <svg>
                    <use href="#icon-plus"></use>
                </svg>
                Kirim laporan
            </button>
        </form>
    </section>

    <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)] mt-[1.2rem]">
        <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
            <div>
                <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">PENGUJIAN</p>
                <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Laporan saya</h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-separate min-w-[760px]">
                <thead>
                    <tr>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Siswa</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Tindakan</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <div class="flex items-center gap-[.7rem]">
                                    <span class="grid h-[31px] w-[31px] place-items-center rounded-[9px] bg-[#6d1a1a] font-[850] text-white">{{ substr($report->siswa->user->nama_lengkap,0,1) }}</span>
                                    <span>
                                        <strong class="block text-[.75rem] text-[#4a1c1c]">{{ $report->siswa->user->nama_lengkap }}</strong>
                                    </span>
                                </div>
                            </td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">{{ $report->jenis_tindakan }}</td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <span class="inline-flex items-center gap-[.35rem] rounded-[99px] px-[.55rem] py-[.3rem] text-[.58rem] font-extrabold capitalize text-[#6d4c41] bg-[#f5e6d3]">{{ $report->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="h-[180px] text-center text-[#a1887f] border-t border-[#fff3e0]">
                                <span class="mx-auto mb-[.7rem] grid h-[42px] w-[42px] place-items-center rounded-[12px] bg-[#ffebee] text-[#b71c1c]">
                                    <svg>
                                        <use href="#icon-file"></use>
                                    </svg>
                                </span>
                                Belum ada laporan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $reports->links() }}
    </section>
</x-layouts.app>
