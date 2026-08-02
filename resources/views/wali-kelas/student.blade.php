@php($title = 'Detail Siswa')
@php($navigation = [['wali-kelas.dashboard', 'Ringkasan kelas','dashboard'], ['wali-kelas.students', 'Daftar siswa','users'], ['wali-kelas.notifications', 'Notifikasi','bell']])

<x-layouts.app :title="$title" :navigation="$navigation">
    <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a] mb-[1.2rem]" href="{{ route('wali-kelas.students') }}">
        <svg>
            <use href="#icon-arrow-right"></use>
        </svg>
        Kembali ke daftar siswa
    </a>

    <x-dashboard
        title="{{ $siswa->user->nama_lengkap }}"
        eyebrow="PROFIL PEMANTAUAN"
        copy="{{ $siswa->nisn }} · {{ $kelas->nama_kelas }}"
    />

    <div class="mb-[1.2rem] grid grid-cols-2 gap-[0.7rem] min-[761px]:gap-4 min-[1051px]:grid-cols-4">
        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#b71c1c] border-t-[3px] border-t-[#b71c1c]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <svg>
                    <use href="#icon-alert"></use>
                </svg>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Pelanggaran</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $siswa->total_poin_pelanggaran }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">Poin tervalidasi</small>
        </article>

        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#f9a825] border-t-[3px] border-t-[#fbc02d66]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <svg>
                    <use href="#icon-heart"></use>
                </svg>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Apresiasi</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $siswa->total_poin_apresiasi }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">Poin positif</small>
        </article>

        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#6d1a1a] border-t-[3px] border-t-[#6d1a1a]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <svg>
                    <use href="#icon-shield"></use>
                </svg>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Saldo</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $siswa->saldo_poin }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">Saldo poin siswa</small>
        </article>

        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#f57f17] border-t-[3px] border-t-[#fbc02d]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <svg>
                    <use href="#icon-tracking"></use>
                </svg>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Status</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $siswa->total_poin_pelanggaran >= 25 ? 'Dipantau' : 'Normal' }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">Threshold kedisiplinan</small>
        </article>
    </div>

    <div class="mt-[1.2rem] grid grid-cols-1 gap-[1.2rem] min-[761px]:grid-cols-[1.05fr_.95fr]">
        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
                <div>
                    <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">RIWAYAT</p>
                    <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Riwayat poin</h3>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse border-separate min-w-[640px]">
                    <thead>
                        <tr>
                            <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Tanggal</th>
                            <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Kategori</th>
                            <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Jenis</th>
                            <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                            <tr>
                                <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">{{ $record->tanggal->format('d/m/Y') }}</td>
                                <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">{{ $record->kategoriPoin->nama_kategori }}</td>
                                <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                    <span class="inline-flex items-center gap-[.35rem] rounded-[99px] px-[.55rem] py-[.3rem] text-[.58rem] font-extrabold capitalize {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-[#5d4037] bg-[#fff9c4]' : 'text-[#c62828] bg-[#ffebee]' }}">
                                        {{ ucfirst($record->kategoriPoin->jenis) }}
                                    </span>
                                </td>
                                <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0] {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-[#5d4037]' : 'text-[#c62828]' }}">
                                    <strong>{{ $record->kategoriPoin->jenis === 'apresiasi' ? '+' : '-' }}{{ $record->kategoriPoin->bobot_poin }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="h-[180px] text-center text-[#a1887f] border-t border-[#fff3e0]">
                                    <span class="mx-auto mb-[.7rem] grid h-[42px] w-[42px] place-items-center rounded-[12px] bg-[#fff3e0] text-[#6d1a1a]">
                                        <svg>
                                            <use href="#icon-clock"></use>
                                        </svg>
                                    </span>
                                    Belum ada riwayat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $records->links() }}
        </section>

        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
                <div>
                    <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">PERINGATAN</p>
                    <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Status peringatan</h3>
                </div>
            </div>

            @forelse($alerts as $alert)
                <div class="px-6 py-4 border-b border-[#fce4c4] {{ $alert->is_resolved ? 'opacity-[.65]' : '' }}">
                    <strong class="flex items-center gap-[.35rem]">
                        <svg>
                            <use href="#icon-{{ $alert->level === 'berat' ? 'alert' : ($alert->level === 'sedang' ? 'warning' : 'info') }}"></use>
                        </svg>
                        {{ $alert->judul }}
                    </strong>
                    <p class="mt-[.3rem] text-[.78rem] leading-[1.5] text-[#8c6d6d]">{{ $alert->pesan }}</p>
                    <small class="text-[#a1887f]">
                        {{ $alert->is_resolved ? 'Sudah ditindaklanjuti' : 'Dalam pemantauan' }}
                    </small>
                </div>
            @empty
                <p class="p-6 text-center text-[#8c6d6d]">Tidak ada peringatan untuk siswa ini.</p>
            @endforelse
        </section>
    </div>
</x-layouts.app>
