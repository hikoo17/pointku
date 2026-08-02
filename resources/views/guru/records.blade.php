@php($title='Catatan Poin')
@php($navigation=[['guru.dashboard','Ringkasan','dashboard'],['guru.records','Catatan poin','note'],['guru.students','Rekap siswa','users'],['guru.reports','Laporan kesiswaan','file'],['guru.letters','Surat panggilan','letter']])

<x-layouts.app :title="$title" :navigation="$navigation">
    <x-dashboard
        title="Catatan poin"
        eyebrow="KEJADIAN SISWA"
        copy="Catat pelanggaran atau apresiasi dengan kronologi yang dapat ditindaklanjuti."
    />

    <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
        <form method="POST" action="{{ route('guru.records.store') }}" enctype="multipart/form-data" class="p-[1.5rem]">
            @csrf
            <div class="grid grid-cols-1 gap-[1rem] min-[461px]:grid-cols-2">
                <label class="grid gap-[.55rem] text-[.78rem] font-bold text-[#5d4037]">
                    Siswa
                    <select name="siswa_id" required class="min-w-0 flex-1 rounded-[11px] border border-[#fce4c4] bg-white p-[.9rem_1rem] text-[#4a1c1c] outline-none">
                        <option value="">Pilih siswa</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->user->nama_lengkap }} · {{ $student->nisn }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-[.55rem] text-[.78rem] font-bold text-[#5d4037]">
                    Kategori
                    <select name="kategori_poin_id" required class="min-w-0 flex-1 rounded-[11px] border border-[#fce4c4] bg-white p-[.9rem_1rem] text-[#4a1c1c] outline-none">
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ ucfirst($category->jenis) }} · {{ $category->nama_kategori }} ({{ $category->bobot_poin }})
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-[.55rem] text-[.78rem] font-bold text-[#5d4037]">
                    Tanggal
                    <input type="date" name="tanggal" value="{{ now()->format('Y-m-d') }}" required class="min-w-0 flex-1 rounded-[11px] border border-[#fce4c4] bg-white p-[.9rem_1rem] text-[#4a1c1c] outline-none">
                </label>
                <label class="grid gap-[.55rem] text-[.78rem] font-bold text-[#5d4037]">
                    Bukti foto
                    <input type="file" name="bukti_foto" accept="image/*" class="min-w-0 flex-1 rounded-[11px] border border-[#fce4c4] bg-white p-[.9rem_1rem] text-[#4a1c1c] outline-none">
                </label>
            </div>

            <label class="mt-4 grid gap-[.55rem] text-[.78rem] font-bold text-[#5d4037]">
                Kronologi
                <textarea name="keterangan" required placeholder="Jelaskan kejadian secara objektif" class="min-h-[120px] min-w-0 flex-1 rounded-[11px] border border-[#fce4c4] bg-white p-[.9rem_1rem] text-[#4a1c1c] outline-none resize-y"></textarea>
            </label>

            <button class="mt-5 inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border-0 bg-[#6d1a1a] px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-white shadow-[0_8px_20px_#6d1a1a38] transition hover:-translate-y-px hover:bg-[#5a1515]" type="submit">
                <svg>
                    <use href="#icon-plus"></use>
                </svg>
                Simpan catatan
            </button>
        </form>
    </section>

    <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)] mt-[1.2rem]">
        <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
            <div>
                <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">RIWAYAT</p>
                <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Riwayat dan validasi</h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-separate min-w-[760px]">
                <thead>
                    <tr>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Siswa</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Kategori</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Tanggal</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Status</th>
                        <th class="px-[1.5rem] py-[.75rem] text-center text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <div class="flex items-center gap-[.7rem]">
                                    <span class="grid h-[31px] w-[31px] place-items-center rounded-[9px] bg-[#6d1a1a] font-[850] text-white">{{ substr($record->siswa->user->nama_lengkap,0,1) }}</span>
                                    <span>
                                        <strong class="block text-[.75rem] text-[#4a1c1c]">{{ $record->siswa->user->nama_lengkap }}</strong>
                                        <small class="mt-[.18rem] block text-[.61rem] text-[#a1887f]">{{ $record->siswa->nisn ?? '-' }}</small>
                                    </span>
                                </div>
                            </td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <span class="inline-flex items-center gap-[.35rem] rounded-[99px] px-[.55rem] py-[.3rem] text-[.58rem] font-extrabold capitalize {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-[#5d4037] bg-[#fff9c4]' : 'text-[#c62828] bg-[#ffebee]' }}">
                                    {{ ucfirst($record->kategoriPoin->jenis) }}
                                </span>
                                <small class="mt-[.18rem] block text-[.61rem] text-[#a1887f]">{{ $record->kategoriPoin->nama_kategori }}</small>
                            </td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">{{ $record->tanggal->format('d/m/Y') }}</td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <span class="inline-flex items-center gap-[.35rem] rounded-[99px] px-[.55rem] py-[.3rem] text-[.58rem] font-extrabold capitalize text-[#6d4c41] bg-[#f5e6d3]">{{ $record->status_validasi }}</span>
                            </td>
                            <td class="px-[1.5rem] py-[1rem] text-center border-t border-[#fff3e0]">
                                <a class="inline-flex items-center gap-1 text-[.7rem] font-extrabold text-[#6d1a1a] hover:underline" href="{{ route('guru.records.show', $record) }}">
                                    <svg class="h-4 w-4">
                                        <use href="#icon-eye"></use>
                                    </svg>
                                    Detail
                                </a>
                                @if(auth()->user()->hasRole('Guru BK') && $record->status_validasi === 'menunggu_validasi')
                                    <form method="POST" action="{{ route('guru.records.validate',$record) }}" class="inline-flex items-center gap-[.4rem] ml-2">
                                        @csrf
                                        <button class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border-0 bg-[#6d1a1a] px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-white shadow-[0_8px_20px_#6d1a1a38] transition hover:-translate-y-px hover:bg-[#5a1515]" name="status_validasi" value="disetujui" title="Setujui">
                                            <svg>
                                                <use href="#icon-check"></use>
                                            </svg>
                                        </button>
                                        <button class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border border-[#fce4c4] bg-white px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-[#5d4037] shadow-[0_5px_18px_rgba(74,28,28,.03)] transition hover:bg-[#fff8e1]" name="status_validasi" value="ditolak" title="Tolak">
                                            <svg>
                                                <use href="#icon-close"></use>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="h-[180px] text-center text-[#a1887f] border-t border-[#fff3e0]">
                                <span class="mx-auto mb-[.7rem] grid h-[42px] w-[42px] place-items-center rounded-[12px] bg-[#fff3e0] text-[#6d1a1a]">
                                    <svg>
                                        <use href="#icon-note"></use>
                                    </svg>
                                </span>
                                Belum ada catatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $records->links() }}
    </section>
</x-layouts.app>
