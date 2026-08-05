@php($title = 'Dashboard Saya')

<x-layouts.app :title="$title" >
    <x-dashboard
        title="Halo, {{ auth()->user()->nama_lengkap }}"
        eyebrow="RUANG SISWA"
        copy="Lihat perkembangan kedisiplinan, apresiasi, dan tindak lanjut yang tercatat untukmu."
    />

    <div class="mb-[1.2rem] grid grid-cols-2 gap-[0.7rem] min-[761px]:gap-4 min-[1051px]:grid-cols-4">
        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#b71c1c] border-t-[3px] border-t-[#b71c1c]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <svg>
                    <use href="#icon-alert"></use>
                </svg>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Pelanggaran</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $student->total_poin_pelanggaran }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">Poin yang telah disetujui</small>
        </article>

        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#f9a825] border-t-[3px] border-t-[#fbc02d66]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <svg>
                    <use href="#icon-heart"></use>
                </svg>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Apresiasi</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $student->total_poin_apresiasi }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">Poin positif</small>
        </article>

        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#6d1a1a] border-t-[3px] border-t-[#6d1a1a]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <svg>
                    <use href="#icon-shield"></use>
                </svg>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Saldo poin</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $student->saldo_poin }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">Pelanggaran dan apresiasi</small>
        </article>

        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#f57f17] border-t-[3px] border-t-[#fbc02d]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <svg>
                    <use href="#icon-school"></use>
                </svg>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Kelas</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $student->kelas->nama_kelas ?? '-' }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">NISN {{ $student->nisn }}</small>
        </article>
    </div>

    <div class="mt-[1.2rem] grid grid-cols-1 gap-[1.2rem] min-[761px]:grid-cols-[1.05fr_.95fr]">
        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
                <div>
                    <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">AKTIVITAS TERBARU</p>
                    <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Riwayat poin</h3>
                </div>
                <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a]" href="{{ route('siswa.history') }}">
                    Lihat semua
                    <svg>
                        <use href="#icon-arrow-right"></use>
                    </svg>
                </a>
            </div>

            @forelse ($records as $record)
                <a class="mx-6 flex items-center gap-[.8rem] border-b border-[#fce4c4] py-4" href="{{ route('siswa.history.show', $record) }}">
                    <span class="grid h-[34px] w-[34px] place-items-center rounded-[10px] bg-[#ffe0b2] text-[#bf360c] font-[900] {{ $record->kategoriPoin->jenis === 'pelanggaran' ? 'bg-[#ffebee] text-[#c62828]' : '' }}">
                        <svg>
                            <use href="#icon-{{ $record->kategoriPoin->jenis === 'apresiasi' ? 'heart' : 'alert' }}"></use>
                        </svg>
                    </span>
                    <span class="grid gap-[.15rem] flex-1">
                        <strong class="block text-[.75rem] text-[#4a1c1c]">{{ $record->kategoriPoin->nama_kategori }}</strong>
                        <small class="mt-[.18rem] block text-[.61rem] text-[#a1887f]">{{ $record->tanggal->translatedFormat('d M Y') }}</small>
                    </span>
                    <b class="text-[.82rem]">{{ $record->kategoriPoin->bobot_poin }} poin</b>
                </a>
            @empty
                <p class="p-6 text-center text-[#8c6d6d]">Belum ada riwayat poin yang disetujui.</p>
            @endforelse
        </section>

        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
                <div>
                    <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">TINDAK LANJUT</p>
                    <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Notifikasi saya</h3>
                </div>
                <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a]" href="{{ route('siswa.notifications') }}">
                    Lihat semua
                    <svg>
                        <use href="#icon-arrow-right"></use>
                    </svg>
                </a>
            </div>

            @forelse ($alerts as $alert)
                <div class="px-6 py-4 border-b border-[#fce4c4] {{ $alert->dibaca_pada ? 'opacity-[.65]' : '' }}">
                    <strong class="flex items-center gap-[.35rem]">
                        <svg>
                            <use href="#icon-{{ $alert->level === 'berat' ? 'alert' : ($alert->level === 'sedang' ? 'warning' : 'info') }}"></use>
                        </svg>
                        {{ $alert->judul }}
                    </strong>
                    <p class="mt-[.3rem] text-[.78rem] leading-[1.5] text-[#8c6d6d]">{{ $alert->pesan }}</p>
                    <small class="text-[#a1887f]">{{ $alert->created_at->diffForHumans() }}</small>
                </div>
            @empty
                <p class="p-6 text-center text-[#8c6d6d]">Belum ada peringatan atau tindak lanjut.</p>
            @endforelse
        </section>
    </div>
</x-layouts.app>
