@php($title='Detail Poin')
@php($navigation=[['guru.dashboard','Ringkasan','dashboard'],['guru.records','Catatan poin','note'],['guru.students','Rekap siswa','users'],['guru.reports','Laporan kesiswaan','file'],['guru.letters','Surat panggilan','letter']])

<x-layouts.app :title="$title" :navigation="$navigation">
    <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a] mb-[1.2rem]" href="{{ route('guru.records') }}">
        <svg>
            <use href="#icon-arrow-right"></use>
        </svg>
        Kembali ke catatan poin
    </a>

    <x-dashboard
        title="{{ $record->kategoriPoin->nama_kategori }}"
        eyebrow="DETAIL KEJADIAN"
        copy="Informasi lengkap catatan yang dapat divalidasi oleh Guru BK."
    />

    <div class="grid grid-cols-1 gap-[1rem] min-[461px]:grid-cols-[minmax(280px,.8fr)_minmax(0,1.2fr)]">
        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <dl class="m-0">
                <div class="flex justify-between gap-[1rem] border-b border-[#fce4c4] p-[1rem]">
                    <dt class="text-[.75rem] text-[#8c6d6d]">Jenis</dt>
                    <dd>
                        <span class="inline-flex items-center gap-[.35rem] rounded-[99px] px-[.55rem] py-[.3rem] text-[.58rem] font-extrabold capitalize {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-[#5d4037] bg-[#fff9c4]' : 'text-[#c62828] bg-[#ffebee]' }}">
                            {{ ucfirst($record->kategoriPoin->jenis) }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between gap-[1rem] border-b border-[#fce4c4] p-[1rem]">
                    <dt class="text-[.75rem] text-[#8c6d6d]">Bobot poin</dt>
                    <dd class="text-[.8rem] font-bold text-right {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-[#5d4037]' : 'text-[#c62828]' }}">
                        <strong>{{ $record->kategoriPoin->jenis === 'apresiasi' ? '+' : '-' }}{{ $record->kategoriPoin->bobot_poin }}</strong>
                    </dd>
                </div>
                <div class="flex justify-between gap-[1rem] border-b border-[#fce4c4] p-[1rem]">
                    <dt class="text-[.75rem] text-[#8c6d6d]">Tanggal</dt>
                    <dd class="m-0 text-[.8rem] font-bold text-right">{{ $record->tanggal->translatedFormat('d F Y') }}</dd>
                </div>
                <div class="flex justify-between gap-[1rem] border-b border-[#fce4c4] p-[1rem]">
                    <dt class="text-[.75rem] text-[#8c6d6d]">Pencatat</dt>
                    <dd class="m-0 text-[.8rem] font-bold text-right">{{ $record->pencatat->nama_lengkap }}</dd>
                </div>
                <div class="flex justify-between gap-[1rem] border-b border-[#fce4c4] p-[1rem]">
                    <dt class="text-[.75rem] text-[#8c6d6d]">Siswa</dt>
                    <dd class="m-0 text-[.8rem] font-bold text-right">{{ $record->siswa->user->nama_lengkap }} · {{ $record->siswa->nisn }}</dd>
                </div>
                <div class="flex justify-between gap-[1rem] p-[1rem]">
                    <dt class="text-[.75rem] text-[#8c6d6d]">Status</dt>
                    <dd class="m-0">
                        @if($record->status_validasi === 'disetujui')
                            <span class="inline-flex items-center gap-[.35rem] rounded-[7px] px-[.55rem] py-[.35rem] text-[.58rem] font-extrabold tracking-[.08em] text-[#fff] bg-[#2e7d32]">Disetujui</span>
                        @elseif($record->status_validasi === 'ditolak')
                            <span class="inline-flex items-center gap-[.35rem] rounded-[7px] px-[.55rem] py-[.35rem] text-[.58rem] font-extrabold tracking-[.08em] text-[#fff] bg-[#c62828]">Ditolak</span>
                        @else
                            <span class="inline-flex items-center gap-[.35rem] rounded-[7px] px-[.55rem] py-[.35rem] text-[.58rem] font-extrabold tracking-[.08em] text-[#5d4037] bg-[#f5e6d3]">Menunggu validasi</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </section>

        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="p-[1.5rem]">
                <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">KRONOLOGI</p>
                <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Detail catatan</h3>
                <p class="mt-[.3rem] text-[.78rem] leading-[1.8] whitespace-pre-line text-[#6d4c41]">{{ $record->keterangan }}</p>

                @if($record->bukti_foto)
                    <a class="mt-5 inline-flex items-center gap-[.55rem] min-h-[42px] rounded-[10px] border border-[#fce4c4] bg-white px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-[#5d4037] shadow-[0_5px_18px_rgba(74,28,28,.03)] transition hover:bg-[#fff8e1]" href="{{ asset('storage/'.$record->bukti_foto) }}" target="_blank" rel="noopener">
                        <svg>
                            <use href="#icon-eye"></use>
                        </svg>
                        Lihat bukti
                    </a>
                @endif

                @if($record->status_validasi === 'menunggu_validasi' && auth()->user()->hasRole('Guru BK'))
                    <div class="mt-[1.2rem] flex items-center gap-[.7rem]">
                        <form method="POST" action="{{ route('guru.records.validate',$record) }}">
                            @csrf
                            <button class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border-0 bg-[#6d1a1a] px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-white shadow-[0_8px_20px_#6d1a1a38] transition hover:-translate-y-px hover:bg-[#5a1515]" name="status_validasi" value="disetujui">
                                <svg>
                                    <use href="#icon-check"></use>
                                </svg>
                                Setujui
                            </button>
                        </form>
                        <form method="POST" action="{{ route('guru.records.validate',$record) }}">
                            @csrf
                            <button class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border border-[#fce4c4] bg-white px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-[#5d4037] shadow-[0_5px_18px_rgba(74,28,28,.03)] transition hover:bg-[#fff8e1]" name="status_validasi" value="ditolak">
                                <svg>
                                    <use href="#icon-close"></use>
                                </svg>
                                Tolak
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-layouts.app>
