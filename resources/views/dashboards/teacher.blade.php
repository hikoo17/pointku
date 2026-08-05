@php($title='Dashboard '.auth()->user()->role->nama_role)

<x-layouts.app :title="$title" >
    <x-dashboard
        title="Ruang kerja Guru BK"
        eyebrow="VALIDASI DAN PENDAMPINGAN"
        copy="Periksa catatan kejadian, pantau saldo siswa, lalu kirim penanganan resmi saat threshold terlewati."
    />

    <div class="mb-[1.2rem] grid grid-cols-2 gap-[0.7rem] min-[761px]:gap-4 min-[1051px]:grid-cols-4">
        @foreach([
            ['Siswa terdata', 'studentCount', 'users', 'text-[#6d1a1a] border-t-[3px] border-t-[#6d1a1a]'],
            ['Menunggu validasi', 'pendingCount', 'clock', 'text-[#f57f17] border-t-[3px] border-t-[#fbc02d]'],
            ['Laporan pending', 'reportCount', 'file', 'text-[#b71c1c] border-t-[3px] border-t-[#b71c1c]'],
            ['Surat otomatis', 'letterCount', 'letter', 'text-[#f9a825] border-t-[3px] border-t-[#fbc02d66]'],
        ] as [$label,$key,$icon,$theme])
            <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] {{ $theme }}">
                <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                    <svg>
                        <use href="#icon-{{ $icon }}"></use>
                    </svg>
                </span>
                <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">{{ $label }}</span>
                <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $$key }}</strong>
                <small class="text-[.62rem] text-[#8c6d6d]">Dalam sistem</small>
            </article>
        @endforeach
    </div>

    <div class="mt-[1.2rem] grid grid-cols-1 gap-[1.2rem] min-[761px]:grid-cols-[1.05fr_.95fr]">
        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
                <div>
                    <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">VALIDASI UTAMA</p>
                    <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Catatan menunggu pemeriksaan</h3>
                </div>
                <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a]" href="{{ route('guru.records') }}">
                    Buka semua
                    <svg>
                        <use href="#icon-arrow-right"></use>
                    </svg>
                </a>
            </div>

            @forelse($records->where('status_validasi','menunggu_validasi') as $record)
                <div class="mx-6 flex items-center gap-[.8rem] border-b border-[#fce4c4] py-4">
                    <span class="inline-block h-[10px] w-[10px] rounded-full {{ $record->kategoriPoin->jenis === 'pelanggaran' ? 'bg-[#b71c1c]' : 'bg-[#fbc02d]' }}"></span>
                    <div class="flex-1">
                        <strong class="block text-[.74rem] text-[#4a1c1c]">{{ $record->siswa->user->nama_lengkap }}</strong>
                        <small class="mt-[.2rem] block text-[.63rem] text-[#8d6e63]">{{ $record->kategoriPoin->nama_kategori }} Â· {{ $record->tanggal->format('d/m/Y') }}</small>
                    </div>
                    <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a] hover:underline" href="{{ route('guru.records.show', $record) }}">
                        <svg class="h-4 w-4">
                            <use href="#icon-eye"></use>
                        </svg>
                        Detail
                    </a>
                    <form method="POST" action="{{ route('guru.records.validate',$record) }}" class="flex items-center gap-[.4rem]">
                        @csrf
                        <button class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border-0 bg-[#6d1a1a] px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-white shadow-[0_8px_20px_#6d1a1a38] transition hover:-translate-y-px hover:bg-[#5a1515]" name="status_validasi" value="disetujui">Setujui</button>
                        <button class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border border-[#fce4c4] bg-white px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-[#5d4037] shadow-[0_5px_18px_rgba(74,28,28,.03)] transition hover:bg-[#fff8e1]" name="status_validasi" value="ditolak">Tolak</button>
                    </form>
                </div>
            @empty
                <p class="p-6 text-center text-[#8c6d6d]">Tidak ada catatan yang menunggu validasi.</p>
            @endforelse
        </section>

        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
                <div>
                    <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">ALUR BK</p>
                    <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Catat, validasi, dan tindak lanjuti.</h3>
                </div>
            </div>
            <div class="p-[1.35rem]">
                <p class="mb-[1rem] text-[.8rem] leading-[1.7] text-[#fff]">Setelah catatan disetujui, Guru BK dapat mengirim laporan ke Kesiswaan untuk keputusan resmi.</p>
                <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#fff] underline" href="{{ route('guru.reports') }}">
                    Kelola laporan
                    <svg>
                        <use href="#icon-arrow-right"></use>
                    </svg>
                </a>
            </div>
        </section>
    </div>
</x-layouts.app>
