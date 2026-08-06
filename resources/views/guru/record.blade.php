@php
    $title = 'Detail Poin';
    $statusStyles = [
        'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
        'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200/60',
        'menunggu_validasi' => 'bg-amber-50 text-amber-700 border-amber-200/60',
        'draft' => 'bg-slate-100 text-slate-600 border-slate-200/60',
    ];
    $statusLabel = [
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'menunggu_validasi' => 'Menunggu',
        'draft' => 'Draft',
    ];
@endphp

<x-layouts.app :title="$title">
    <a class="mb-5 inline-flex items-center gap-1.5 text-[0.7rem] font-extrabold text-[#5c1919] hover:underline" href="{{ route('guru.records') }}">
        <i data-lucide="arrow-left" class="h-4 w-4"></i>
        Kembali ke catatan poin
    </a>

    <x-dashboard
        title="{{ $record->kategoriPoin->nama_kategori }}"
        eyebrow="DETAIL KEJADIAN"
        copy="Informasi lengkap catatan yang dapat divalidasi oleh Guru BK."
    />

    <div class="grid grid-cols-1 gap-5 min-[761px]:grid-cols-[minmax(280px,.8fr)_minmax(0,1.2fr)]">
        <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
            <div class="border-b border-slate-100 px-5 py-4">
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">RINGKASAN</span>
                <h3 class="text-base font-bold text-slate-900">Informasi catatan</h3>
            </div>
            <dl class="m-0">
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-3.5">
                    <dt class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">Jenis</dt>
                    <dd class="m-0">
                        <span class="inline-flex items-center rounded-md border px-2.5 py-1 text-[0.68rem] font-semibold capitalize {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'bg-emerald-50 text-emerald-700 border-emerald-200/60' : 'bg-rose-50 text-rose-700 border-rose-200/60' }}">
                            {{ ucfirst($record->kategoriPoin->jenis) }}
                        </span>
                    </dd>
                </div>
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-3.5">
                    <dt class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">Bobot poin</dt>
                    <dd class="m-0 text-sm font-bold {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $record->kategoriPoin->jenis === 'apresiasi' ? '+' : '-' }}{{ $record->kategoriPoin->bobot_poin }}
                    </dd>
                </div>
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-3.5">
                    <dt class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">Tanggal</dt>
                    <dd class="m-0 text-sm font-medium text-slate-700">{{ $record->tanggal->translatedFormat('d F Y') }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-3.5">
                    <dt class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">Pencatat</dt>
                    <dd class="m-0 text-sm font-medium text-slate-700">{{ $record->pencatat->nama_lengkap }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-3.5">
                    <dt class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">Siswa</dt>
                    <dd class="m-0 text-right text-sm font-medium text-slate-700">{{ $record->siswa->user->nama_lengkap }} &middot; {{ $record->siswa->nisn }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                    <dt class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">Status</dt>
                    <dd class="m-0">
                        <span class="inline-flex items-center rounded-md border px-2.5 py-1 text-[0.68rem] font-semibold capitalize {{ $statusStyles[$record->status_validasi] ?? 'bg-slate-100 text-slate-600 border-slate-200/60' }}">
                            {{ $statusLabel[$record->status_validasi] ?? $record->status_validasi }}
                        </span>
                    </dd>
                </div>
            </dl>
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
            <div class="border-b border-slate-100 px-5 py-4">
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">KRONOLOGI</span>
                <h3 class="text-base font-bold text-slate-900">Detail catatan</h3>
            </div>
            <div class="p-5">
                <p class="whitespace-pre-line text-sm leading-relaxed text-slate-600">{{ $record->keterangan }}</p>

                @if($record->bukti_foto_list)
                    <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach($record->bukti_foto_list as $index => $foto)
                            <a class="group relative aspect-square overflow-hidden rounded-lg border border-slate-200 bg-slate-50" href="{{ asset('storage/'.$foto) }}" target="_blank" rel="noopener">
                                <img class="h-full w-full object-cover transition group-hover:scale-105" src="{{ asset('storage/'.$foto) }}" alt="Bukti foto {{ $index + 1 }}" loading="lazy">
                                <span class="absolute inset-x-0 bottom-0 bg-slate-950/65 px-2 py-1.5 text-center text-[0.68rem] font-semibold text-white">Lihat foto {{ $index + 1 }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif

                @if($record->status_validasi === 'menunggu_validasi' && auth()->user()->hasRole('Guru BK'))
                    <div class="mt-5 flex items-center gap-2">
                        <form method="POST" action="{{ route('guru.records.validate', $record) }}">
                            @csrf
                            <button class="inline-flex items-center gap-1.5 rounded-lg bg-[#5c1919] px-3 py-1.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#4a1414]" name="status_validasi" value="disetujui" data-confirm="Catatan poin ini akan disetujui." data-confirm-title="Setujui catatan?" data-confirm-button="Ya, setujui">
                                <i data-lucide="check" class="h-4 w-4"></i>
                                Setujui
                            </button>
                        </form>
                        <form method="POST" action="{{ route('guru.records.validate', $record) }}">
                            @csrf
                            <button class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50" name="status_validasi" value="ditolak" data-confirm="Catatan poin ini akan ditolak." data-confirm-title="Tolak catatan?" data-confirm-button="Ya, tolak">
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
