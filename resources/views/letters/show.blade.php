@php($isBk=auth()->user()->hasRole('Guru BK'))
@php($title='Detail Surat Panggilan')
@php($navigation=$isBk ? [['guru.dashboard','Ringkasan','dashboard'],['guru.records','Catatan poin','note'],['guru.students','Rekap siswa','users'],['guru.reports','Laporan kesiswaan','file'],['guru.letters','Surat panggilan','letter']] : [['kesiswaan.dashboard','Statistik sekolah','dashboard'],['kesiswaan.reports','Laporan masuk','file'],['kesiswaan.letters','Surat panggilan','letter']])

<x-layouts.app :title="$title" :navigation="$navigation">
    <a class="mb-5 inline-flex text-sm font-bold text-[#6d1a1a]" href="{{ route($isBk ? 'guru.letters' : 'kesiswaan.letters') }}">Kembali ke daftar</a>
    <div class="grid gap-6 lg:grid-cols-[1.5fr_.8fr]">
        <section class="rounded-[15px] border border-[#fce4c4] bg-white p-6">
            <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
                <div><p class="text-xs font-bold uppercase tracking-wider text-[#a1887f]">{{ $surat->nomor_surat ?? 'Draf belum bernomor' }}</p><h1 class="mt-1 text-2xl font-extrabold text-[#4a1c1c]">{{ $surat->siswa->user->nama_lengkap }}</h1><p class="text-sm text-[#795548]">{{ $surat->siswa->nisn }} · {{ $surat->siswa->kelas->nama_kelas ?? '-' }}</p></div>
                <span class="rounded-full bg-[#fff3e0] px-3 py-1 text-xs font-extrabold capitalize text-[#6d1a1a]">{{ str_replace('_',' ',$surat->status) }}</span>
            </div>

            @if($isBk && in_array($surat->status,['draft','perlu_revisi']))
                @if($surat->catatan_revisi)<div class="mb-5 rounded-xl border border-[#ffcdd2] bg-[#ffebee] p-4 text-sm text-[#8e2020]"><strong>Catatan revisi:</strong> {{ $surat->catatan_revisi }}</div>@endif
                <form method="POST" action="{{ route('guru.letters.update',$surat) }}" class="space-y-4">@csrf @method('PUT')
                    <label class="block text-sm font-bold">Tanggal surat<input class="mt-1 w-full rounded-xl border border-[#e8d4bd] p-3" type="date" name="tanggal_surat" value="{{ old('tanggal_surat',$surat->tanggal_surat?->format('Y-m-d')) }}" required></label>
                    <label class="block text-sm font-bold">Alasan pemanggilan<textarea class="mt-1 w-full rounded-xl border border-[#e8d4bd] p-3" name="alasan_pemanggilan" rows="4" required>{{ old('alasan_pemanggilan',$surat->alasan_pemanggilan) }}</textarea></label>
                    <label class="block text-sm font-bold">Daftar kejadian<textarea class="mt-1 w-full rounded-xl border border-[#e8d4bd] p-3" name="daftar_kejadian" rows="6">{{ old('daftar_kejadian',$surat->daftar_kejadian) }}</textarea></label>
                    <label class="block text-sm font-bold">Tindakan direkomendasikan<textarea class="mt-1 w-full rounded-xl border border-[#e8d4bd] p-3" name="tindakan_direkomendasikan" rows="3" required>{{ old('tindakan_direkomendasikan',$surat->tindakan_direkomendasikan) }}</textarea></label>
                    <button class="rounded-xl bg-[#6d1a1a] px-5 py-3 text-sm font-bold text-white">Simpan draf</button>
                </form>
            @else
                <dl class="space-y-5 text-sm"><div><dt class="font-extrabold text-[#4a1c1c]">Alasan pemanggilan</dt><dd class="mt-1 whitespace-pre-line text-[#5d4037]">{{ $surat->alasan_pemanggilan }}</dd></div><div><dt class="font-extrabold text-[#4a1c1c]">Daftar kejadian</dt><dd class="mt-1 whitespace-pre-line text-[#5d4037]">{{ $surat->daftar_kejadian ?: '-' }}</dd></div><div><dt class="font-extrabold text-[#4a1c1c]">Tindakan</dt><dd class="mt-1 text-[#5d4037]">{{ $surat->tindakan_direkomendasikan }}</dd></div></dl>
            @endif
        </section>

        <aside class="space-y-6">
            <section class="rounded-[15px] border border-[#fce4c4] bg-white p-5"><h2 class="mb-4 font-extrabold text-[#4a1c1c]">Aksi berikutnya</h2>
                @php($actions=$isBk ? ['draft'=>'diajukan','perlu_revisi'=>'diajukan'] : ['diajukan'=>'disetujui','disetujui'=>'dicetak','dicetak'=>'dikirim','dikirim'=>'selesai'])
                @if(isset($actions[$surat->status]))<form method="POST" action="{{ route($isBk ? 'guru.letters.transition' : 'kesiswaan.letters.transition',$surat) }}">@csrf<input type="hidden" name="status" value="{{ $actions[$surat->status] }}"><button class="w-full rounded-xl bg-[#6d1a1a] px-4 py-3 text-sm font-bold capitalize text-white">{{ str_replace('_',' ',$actions[$surat->status]) }}</button></form>@endif
                @if(!$isBk && $surat->status==='diajukan')<form class="mt-3 space-y-2" method="POST" action="{{ route('kesiswaan.letters.transition',$surat) }}">@csrf<input type="hidden" name="status" value="perlu_revisi"><textarea class="w-full rounded-xl border border-[#e8d4bd] p-3 text-sm" name="catatan" placeholder="Catatan revisi" required></textarea><button class="w-full rounded-xl border border-[#6d1a1a] px-4 py-3 text-sm font-bold text-[#6d1a1a]">Minta revisi</button></form>@endif
                @if(!$isBk && in_array($surat->status, ['disetujui','dicetak','dikirim','selesai'], true))<a class="mt-3 block w-full rounded-xl border border-[#6d1a1a] px-4 py-3 text-center text-sm font-bold text-[#6d1a1a]" target="_blank" href="{{ route('kesiswaan.letters.print',$surat) }}">Cetak / lihat surat</a>@endif
                @if(!isset($actions[$surat->status]) && !(!$isBk && $surat->status==='diajukan'))<p class="text-sm text-[#8d6e63]">Tidak ada aksi untuk role Anda pada status ini.</p>@endif
            </section>
            <section class="rounded-[15px] border border-[#fce4c4] bg-white p-5"><h2 class="mb-4 font-extrabold text-[#4a1c1c]">Riwayat</h2><div class="space-y-4">@forelse($surat->histories as $history)<div class="border-l-2 border-[#f0c99b] pl-3"><p class="text-sm font-bold capitalize">{{ str_replace('_',' ',$history->status_baru) }}</p><p class="text-xs text-[#8d6e63]">{{ $history->user->nama_lengkap }} · {{ $history->created_at->format('d/m/Y H:i') }}</p>@if($history->catatan)<p class="mt-1 text-xs">{{ $history->catatan }}</p>@endif</div>@empty<p class="text-sm text-[#8d6e63]">Belum ada perubahan status.</p>@endforelse</div></section>
        </aside>
    </div>
</x-layouts.app>
