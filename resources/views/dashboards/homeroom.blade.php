@php($title = 'Dashboard Wali Kelas')

<x-layouts.app :title="$title" >
    <x-dashboard
        title="Kelas {{ $kelas->nama_kelas }}"
        eyebrow="RUANG WALI KELAS"
        copy="Pantau perkembangan seluruh siswa di kelas tanpa mengubah transaksi poin."
    />

    <div class="mb-[1.2rem] grid grid-cols-2 gap-[0.7rem] min-[761px]:gap-4 min-[1051px]:grid-cols-4">
        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#6d1a1a] border-t-[3px] border-t-[#6d1a1a]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <i data-lucide="circle-alert" class="block text-[.68rem] font-[750] text-[#8c6d6d]"></i>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Perlu dipantau</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $students->where('total_poin_pelanggaran', '>=', 25)->count() }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">Melewati 25 poin</small>
        </article>

        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#f57f17] border-t-[3px] border-t-[#fbc02d]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <i data-lucide="heart" class="block text-[.68rem] font-[750] text-[#8c6d6d]"></i>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Poin apresiasi</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $students->sum('total_poin_apresiasi') }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">Akumulasi positif</small>
        </article>
    </div>

    <div class="mt-[1.2rem] grid grid-cols-1 gap-[1.2rem] min-[761px]:grid-cols-[1.05fr_.95fr]">
        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
                <div>
                    <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">PRIORITAS PENDAMPINGAN</p>
                    <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Siswa perlu dipantau</h3>
                </div>
                <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a]" href="{{ route('wali-kelas.students', ['status' => 'dipantau']) }}">
                    Lihat semua
                    <i data-lucide="chevron-right" class="h-4 w-4"></i>
                </a>
            </div>
            <div class="divide-y divide-[#fce4c4]">
                @forelse($students->where('total_poin_pelanggaran', '>=', 25) as $student)
                    <a class="flex items-center gap-[.8rem] px-6 py-4 transition hover:bg-[#fff8e1]" href="{{ route('wali-kelas.student', $student) }}">
                        <span class="grid h-[34px] w-[34px] shrink-0 place-items-center rounded-[10px] bg-[#6d1a1a] font-[850] text-white">{{ substr($student->user->nama_lengkap, 0, 1) }}</span>
                        <span class="grid flex-1 gap-[.15rem]">
                            <strong class="block text-[.75rem] text-[#4a1c1c]">{{ $student->user->nama_lengkap }}</strong>
                            <small class="mt-[.18rem] block text-[.61rem] text-[#a1887f]">NISN {{ $student->nisn }}</small>
                        </span>
                        <b class="text-[.75rem]">{{ $student->total_poin_pelanggaran }} poin</b>
                    </a>
                @empty
                    <p class="p-6 text-center text-[#8c6d6d]">Belum ada siswa melewati threshold.</p>
                @endforelse
            </div>
        </section>

        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
                <div>
                    <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">NOTIFIKASI KELAS</p>
                    <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Peringatan terbaru</h3>
                </div>
                <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a]" href="{{ route('wali-kelas.notifications') }}">
                    Lihat semua
                    <i data-lucide="chevron-right" class="h-4 w-4"></i>
                </a>
            </div>
            <div class="divide-y divide-[#fce4c4]">
                @forelse($alerts as $alert)
                    <a class="block px-6 py-4 transition hover:bg-[#fff8e1] {{ $alert->dibaca_pada ? 'opacity-[.65]' : '' }}" href="{{ route('wali-kelas.notifications') }}">
                        <strong class="block text-[.75rem] font-bold text-[#4a1c1c]">
                            {{ $alert->siswa->user->nama_lengkap }} · {{ $alert->judul }}
                        </strong>
                        <p class="mt-[.3rem] text-[.78rem] leading-[1.5] text-[#8c6d6d]">{{ $alert->pesan }}</p>
                        <small class="text-[#a1887f]">{{ $alert->created_at->diffForHumans() }}</small>
                    </a>
                @empty
                    <p class="p-6 text-center text-[#8c6d6d]">Belum ada notifikasi threshold.</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)] mt-[1.2rem]">
        <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
            <div>
                <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">AKTIVITAS TERVALIDASI</p>
                <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Catatan terbaru kelas</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-separate min-w-[760px]">
                <thead>
                    <tr>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Siswa</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Jenis</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Kategori</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Tanggal</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRecords as $record)
                        <tr>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <div class="flex items-center gap-[.7rem]">
                                    <span class="grid h-[31px] w-[31px] place-items-center rounded-[9px] bg-[#6d1a1a] font-[850] text-white">{{ substr($record->siswa->user->nama_lengkap,0,1) }}</span>
                                    <span>{{ $record->siswa->user->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <span class="inline-flex items-center gap-[.35rem] rounded-[99px] px-[.55rem] py-[.3rem] text-[.58rem] font-extrabold capitalize {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-[#5d4037] bg-[#fff9c4]' : 'text-[#c62828] bg-[#ffebee]' }}">
                                    {{ ucfirst($record->kategoriPoin->jenis) }}
                                </span>
                            </td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">{{ $record->kategoriPoin->nama_kategori }}</td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">{{ $record->tanggal->format('d/m/Y') }}</td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0] {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-[#5d4037]' : 'text-[#c62828]' }}">
                                <strong>{{ $record->kategoriPoin->jenis === 'apresiasi' ? '+' : '-' }}{{ $record->kategoriPoin->bobot_poin }}</strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="h-[180px] text-center text-[#a1887f] border-t border-[#fff3e0]">
                                <span class="mx-auto mb-[.7rem] grid h-[42px] w-[42px] place-items-center rounded-[12px] bg-[#fff3e0] text-[#6d1a1a]">
                                    <i data-lucide="notebook-pen" class="h-6 w-6"></i>
                                </span>
                                Belum ada aktivitas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
