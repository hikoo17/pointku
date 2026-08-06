@php
    $title = 'Detail Poin';
@endphp

<x-layouts.app :title="$title">
    <a class="mb-[1.2rem] inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a]" href="{{ route('guru.records') }}">
        <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i>
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
                        <span class="inline-flex items-center gap-[.35rem] rounded-[99px] px-[.55rem] py-[.3rem] text-[.58rem] font-extrabold capitalize {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'bg-[#fff9c4] text-[#5d4037]' : 'bg-[#ffebee] text-[#c62828]' }}">
                            {{ ucfirst($record->kategoriPoin->jenis) }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between gap-[1rem] border-b border-[#fce4c4] p-[1rem]">
                    <dt class="text-[.75rem] text-[#8c6d6d]">Bobot poin</dt>
                    <dd class="text-right text-[.8rem] font-bold {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-[#5d4037]' : 'text-[#c62828]' }}">
                        <strong>{{ $record->kategoriPoin->jenis === 'apresiasi' ? '+' : '-' }}{{ $record->kategoriPoin->bobot_poin }}</strong>
                    </dd>
                </div>
                <div class="flex justify-between gap-[1rem] border-b border-[#fce4c4] p-[1rem]">
                    <dt class="text-[.75rem] text-[#8c6d6d]">Tanggal</dt>
                    <dd class="m-0 text-right text-[.8rem] font-bold">{{ $record->tanggal->translatedFormat('d F Y') }}</dd>
                </div>
                <div class="flex justify-between gap-[1rem] border-b border-[#fce4c4] p-[1rem]">
                    <dt class="text-[.75rem] text-[#8c6d6d]">Pencatat</dt>
                    <dd class="m-0 text-right text-[.8rem] font-bold">{{ $record->pencatat->nama_lengkap }}</dd>
                </div>
                <div class="flex justify-between gap-[1rem] border-b border-[#fce4c4] p-[1rem]">
                    <dt class="text-[.75rem] text-[#8c6d6d]">Siswa</dt>
                    <dd class="m-0 text-right text-[.8rem] font-bold">{{ $record->siswa->user->nama_lengkap }} &middot; {{ $record->siswa->nisn }}</dd>
                </div>
                <div class="flex justify-between gap-[1rem] p-[1rem]">
                    <dt class="text-[.75rem] text-[#8c6d6d]">Status</dt>
                    <dd class="m-0">
                        @if($record->status_validasi === 'disetujui')
                            <span class="inline-flex items-center gap-[.35rem] rounded-[7px] bg-[#2e7d32] px-[.55rem] py-[.35rem] text-[.58rem] font-extrabold tracking-[.08em] text-white">Disetujui</span>
                        @elseif($record->status_validasi === 'ditolak')
                            <span class="inline-flex items-center gap-[.35rem] rounded-[7px] bg-[#c62828] px-[.55rem] py-[.35rem] text-[.58rem] font-extrabold tracking-[.08em] text-white">Ditolak</span>
                        @else
                            <span class="inline-flex items-center gap-[.35rem] rounded-[7px] bg-[#f5e6d3] px-[.55rem] py-[.35rem] text-[.58rem] font-extrabold tracking-[.08em] text-[#5d4037]">Menunggu validasi</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </section>

        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="p-[1.5rem]">
                <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">KRONOLOGI</p>
                <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Detail catatan</h3>
                <p class="mt-[.3rem] whitespace-pre-line text-[.78rem] leading-[1.8] text-[#6d4c41]">{{ $record->keterangan }}</p>

                @if($record->bukti_foto)
                    <a class="mt-5 inline-flex min-h-[42px] items-center gap-[.55rem] rounded-[10px] border border-[#fce4c4] bg-white px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-[#5d4037] shadow-[0_5px_18px_rgba(74,28,28,.03)] transition hover:bg-[#fff8e1]" href="{{ asset('storage/'.$record->bukti_foto) }}" target="_blank" rel="noopener">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                        Lihat bukti
                    </a>
                @endif

                @if($record->status_validasi === 'menunggu_validasi' && auth()->user()->hasRole('Guru BK'))
                    <div class="mt-[1.2rem] flex items-center gap-[.7rem]">
                        <form method="POST" action="{{ route('guru.records.validate', $record) }}">
                            @csrf
                            <button class="inline-flex min-h-[42px] items-center justify-center gap-[.55rem] rounded-[10px] border-0 bg-[#6d1a1a] px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-white shadow-[0_8px_20px_#6d1a1a38] transition hover:-translate-y-px hover:bg-[#5a1515]" name="status_validasi" value="disetujui" data-confirm="Catatan poin ini akan disetujui." data-confirm-title="Setujui catatan?" data-confirm-button="Ya, setujui">
                                <i data-lucide="check" class="h-4 w-4"></i>
                                Setujui
                            </button>
                        </form>
                        <form method="POST" action="{{ route('guru.records.validate', $record) }}">
                            @csrf
                            <button class="inline-flex min-h-[42px] items-center justify-center gap-[.55rem] rounded-[10px] border border-[#fce4c4] bg-white px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-[#5d4037] shadow-[0_5px_18px_rgba(74,28,28,.03)] transition hover:bg-[#fff8e1]" name="status_validasi" value="ditolak" data-confirm="Catatan poin ini akan ditolak." data-confirm-title="Tolak catatan?" data-confirm-button="Ya, tolak">
                                <i data-lucide="x" class="h-4 w-4"></i>
                                Tolak
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-layouts.app>
