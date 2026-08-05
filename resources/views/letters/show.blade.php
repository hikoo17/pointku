@php($isBk = auth()->user()->hasRole('Guru BK'))
@php($title = 'Detail Surat Panggilan')

<x-layouts.app :title="$title">
    {{-- Back Button --}}
    <div class="mb-5">
        <a class="group inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900" href="{{ route($isBk ? 'guru.letters' : 'kesiswaan.letters') }}">
            <svg class="h-3.5 w-3.5 fill-none stroke-current transition-transform duration-200 group-hover:-translate-x-0.5" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Kembali ke daftar
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.5fr_.8fr]">
        {{-- Main Content Section --}}
        <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
            {{-- Header Detail --}}
            <div class="border-b border-slate-100 bg-slate-50/80 p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">
                            {{ $surat->nomor_surat ?? 'Draf belum bernomor' }}
                        </span>
                        <h1 class="mt-1 text-xl font-bold text-slate-900">
                            {{ $surat->siswa->user->nama_lengkap }}
                        </h1>
                        <p class="mt-0.5 text-xs font-semibold text-slate-500">
                            NISN: <span class="text-slate-700">{{ $surat->siswa->nisn }}</span> • Kelas: <span class="text-slate-700">{{ $surat->siswa->kelas->nama_kelas ?? '-' }}</span>
                        </p>
                    </div>
                    <span class="inline-flex items-center rounded-lg bg-amber-50 px-3 py-1 text-xs font-bold capitalize text-amber-800 border border-amber-200/60">
                        {{ str_replace('_', ' ', $surat->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                {{-- Edit Form for Guru BK --}}
                @if($isBk && in_array($surat->status, ['draft', 'perlu_revisi']))
                    @if($surat->catatan_revisi)
                        <div class="mb-5 flex items-start gap-3 rounded-lg border border-rose-200 bg-rose-50 p-4 text-xs text-rose-800">
                            <svg class="h-4 w-4 shrink-0 text-rose-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <strong class="font-bold">Catatan Revisi:</strong>
                                <p class="mt-0.5 text-rose-700">{{ $surat->catatan_revisi }}</p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('guru.letters.update', $surat) }}" class="space-y-4 text-xs">
                        @csrf 
                        @method('PUT')
                        
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tanggal Surat</label>
                            <input class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-800 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $surat->tanggal_surat?->format('Y-m-d')) }}" required>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Alasan Pemanggilan</label>
                            <textarea class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-800 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" name="alasan_pemanggilan" rows="4" required>{{ old('alasan_pemanggilan', $surat->alasan_pemanggilan) }}</textarea>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Daftar Kejadian</label>
                            <textarea class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-800 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" name="daftar_kejadian" rows="5">{{ old('daftar_kejadian', $surat->daftar_kejadian) }}</textarea>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tindakan Direkomendasikan</label>
                            <textarea class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-800 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" name="tindakan_direkomendasikan" rows="3" required>{{ old('tindakan_direkomendasikan', $surat->tindakan_direkomendasikan) }}</textarea>
                        </div>

                        <button class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-2xs transition hover:bg-slate-100 hover:text-slate-900" type="submit">
                            Simpan Draf
                        </button>
                    </form>
                @else
                    {{-- Readonly Details with Solid Cards --}}
                    <div class="space-y-4 text-xs">
                        <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-4">
                            <dt class="font-extrabold text-[0.65rem] uppercase tracking-wider text-[#5c1919]">Alasan Pemanggilan</dt>
                            <dd class="mt-1.5 whitespace-pre-line text-slate-800 leading-relaxed font-medium">{{ $surat->alasan_pemanggilan }}</dd>
                        </div>

                        <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-4">
                            <dt class="font-extrabold text-[0.65rem] uppercase tracking-wider text-[#5c1919]">Daftar Kejadian</dt>
                            <dd class="mt-1.5 whitespace-pre-line text-slate-800 leading-relaxed font-medium">{{ $surat->daftar_kejadian ?: '-' }}</dd>
                        </div>

                        <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-4">
                            <dt class="font-extrabold text-[0.65rem] uppercase tracking-wider text-[#5c1919]">Tindakan Direkomendasikan</dt>
                            <dd class="mt-1.5 text-slate-800 leading-relaxed font-semibold">{{ $surat->tindakan_direkomendasikan }}</dd>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- Sidebar Section --}}
        <aside class="space-y-6">
            {{-- Aksi --}}
            <section class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs">
                <h2 class="mb-4 text-xs font-bold uppercase tracking-wider text-slate-800">Aksi Berikutnya</h2>
                
                @php($actions = $isBk ? ['draft' => 'diajukan', 'perlu_revisi' => 'diajukan'] : ['diajukan' => 'disetujui', 'disetujui' => 'dicetak', 'dicetak' => 'dikirim', 'dikirim' => 'selesai'])
                
                @if(isset($actions[$surat->status]))
                    <form method="POST" action="{{ route($isBk ? 'guru.letters.transition' : 'kesiswaan.letters.transition', $surat) }}">
                        @csrf
                        <input type="hidden" name="status" value="{{ $actions[$surat->status] }}">
                        <button class="w-full rounded-lg bg-[#5c1919] px-4 py-2.5 text-xs font-bold capitalize text-white shadow-2xs transition hover:bg-[#4a1414]" type="submit">
                            {{ str_replace('_', ' ', $actions[$surat->status]) }}
                        </button>
                    </form>
                @endif

                @if(!$isBk && $surat->status === 'diajukan')
                    <form class="mt-3 space-y-2.5" method="POST" action="{{ route('kesiswaan.letters.transition', $surat) }}">
                        @csrf
                        <input type="hidden" name="status" value="perlu_revisi">
                        <textarea class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs text-slate-800 outline-none transition focus:border-slate-400 focus:bg-white" name="catatan" placeholder="Tulis catatan revisi..." required></textarea>
                        <button class="w-full rounded-lg border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-bold text-rose-700 transition hover:bg-rose-100" type="submit">
                            Minta Revisi
                        </button>
                    </form>
                @endif

                @if(!$isBk && in_array($surat->status, ['disetujui', 'dicetak', 'dikirim', 'selesai'], true))
                    <a class="mt-3 block w-full text-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900" target="_blank" href="{{ route('kesiswaan.letters.print', $surat) }}">
                        Cetak / Lihat Surat
                    </a>
                @endif

                @if(!isset($actions[$surat->status]) && !(!$isBk && $surat->status === 'diajukan'))
                    <div class="rounded-lg bg-slate-50 p-3 text-center border border-slate-100 mt-2">
                        <p class="text-xs font-medium text-slate-400">Tidak ada aksi untuk role Anda pada status ini.</p>
                    </div>
                @endif
            </section>

            {{-- Riwayat --}}
            <section class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs">
                <h2 class="mb-4 text-xs font-bold uppercase tracking-wider text-slate-800">Riwayat Status</h2>
                
                <div class="space-y-4">
                    @forelse($surat->histories as $history)
                        <div class="relative pl-3.5 border-l-2 border-[#5c1919]">
                            <p class="text-xs font-bold text-slate-900 capitalize">{{ str_replace('_', ' ', $history->status_baru) }}</p>
                            <p class="text-[0.68rem] font-medium text-slate-400 mt-0.5">{{ $history->user->nama_lengkap }} • {{ $history->created_at->format('d/m/Y H:i') }}</p>
                            @if($history->catatan)
                                <p class="mt-1 rounded-md bg-amber-50/60 p-2 text-xs text-amber-900 border border-amber-200/40 italic">"{{ $history->catatan }}"</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs font-medium text-slate-400">Belum ada perubahan status.</p>
                    @endforelse
                </div>
            </section>
        </aside>
    </div>
</x-layouts.app>