@php($title = 'Dashboard Saya')

<x-layouts.app :title="$title" >
    <x-dashboard
        title="Halo, {{ auth()->user()->nama_lengkap }}"
        eyebrow="RUANG SISWA"
        copy="Lihat perkembangan kedisiplinan, apresiasi, dan tindak lanjut yang tercatat untukmu."
    />

    {{-- Stats Grid --}}
    <div class="mb-6 grid grid-cols-2 gap-4 min-[1051px]:grid-cols-4">
        <article class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200/80 border-t-[3px] border-t-rose-600 bg-white p-4.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md min-[761px]:p-5">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">Pelanggaran</span>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border bg-rose-50 border-rose-100 text-rose-700">
                    <i data-lucide="circle-alert" class="h-4 w-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <strong class="block text-3xl font-bold tracking-tight text-slate-900">{{ $student->total_poin_pelanggaran }}</strong>
                <small class="mt-0.5 flex items-center gap-1 text-[0.65rem] font-medium text-slate-400">Poin yang telah disetujui</small>
            </div>
        </article>

        <article class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200/80 border-t-[3px] border-t-amber-500 bg-white p-4.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md min-[761px]:p-5">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">Apresiasi</span>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border bg-amber-50 border-amber-100 text-amber-700">
                    <i data-lucide="heart" class="h-4 w-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <strong class="block text-3xl font-bold tracking-tight text-slate-900">{{ $student->total_poin_apresiasi }}</strong>
                <small class="mt-0.5 flex items-center gap-1 text-[0.65rem] font-medium text-slate-400">Poin positif</small>
            </div>
        </article>

        <article class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200/80 border-t-[3px] border-t-[#5c1919] bg-white p-4.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md min-[761px]:p-5">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">Saldo poin</span>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border bg-[#5c1919]/5 border-[#5c1919]/10 text-[#5c1919]">
                    <i data-lucide="shield" class="h-4 w-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <strong class="block text-3xl font-bold tracking-tight text-slate-900">{{ $student->saldo_poin }}</strong>
                <small class="mt-0.5 flex items-center gap-1 text-[0.65rem] font-medium text-slate-400">Pelanggaran dan apresiasi</small>
            </div>
        </article>

        <article class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200/80 border-t-[3px] border-t-blue-500 bg-white p-4.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md min-[761px]:p-5">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">Kelas</span>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border bg-blue-50 border-blue-100 text-blue-700">
                    <i data-lucide="school" class="h-4 w-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <strong class="block text-3xl font-bold tracking-tight text-slate-900">{{ $student->kelas->nama_kelas ?? '-' }}</strong>
                <small class="mt-0.5 flex items-center gap-1 text-[0.65rem] font-medium text-slate-400">NISN {{ $student->nisn }}</small>
            </div>
        </article>
    </div>

    {{-- Content Grid --}}
    <div class="mt-6 grid grid-cols-1 gap-6 min-[761px]:grid-cols-2">
        {{-- Riwayat poin --}}
        <section class="flex flex-col overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">AKTIVITAS TERBARU</span>
                    <h3 class="text-base font-bold text-slate-900">Riwayat poin</h3>
                </div>
                <a class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900" href="{{ route('siswa.history') }}">
                    Lihat semua
                    <i data-lucide="arrow-right" class="h-3.5 w-3.5 fill-none stroke-current transition-transform duration-200 group-hover:translate-x-0.5"></i>
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($records as $record)
                    <div class="flex items-start gap-3 px-5 py-3.5">
                        <span class="mt-0.5 grid h-7 w-7 shrink-0 place-items-center rounded-lg {{ $record->kategoriPoin->jenis === 'pelanggaran' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' }}">
                            <i data-lucide="{{ $record->kategoriPoin->jenis === 'pelanggaran' ? 'circle-alert' : 'heart' }}" class="h-4 w-4"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <strong class="block truncate text-xs font-bold text-slate-800">{{ $record->kategoriPoin->nama_kategori }}</strong>
                            <p class="mt-0.5 truncate text-[0.68rem] font-medium text-slate-500">{{ $record->keterangan }}</p>
                        </div>
                        <small class="shrink-0 text-[0.68rem] font-medium text-slate-400">{{ $record->tanggal->format('d/m/Y') }}</small>
                    </div>
                @empty
                    <div class="grid min-h-36 place-items-center px-5 py-8 text-center">
                        <div>
                            <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-full bg-emerald-50 text-emerald-600">
                                <i data-lucide="check" class="h-5 w-5"></i>
                            </span>
                            <strong class="block text-xs font-bold text-slate-700">Belum ada riwayat</strong>
                            <p class="mt-1 text-[0.68rem] font-medium text-slate-400">Riwayat poin yang disetujui akan tampil di sini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Notifikasi saya --}}
        <section class="flex flex-col overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">TINDAK LANJUT</span>
                    <h3 class="text-base font-bold text-slate-900">Notifikasi saya</h3>
                </div>
                <a class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900" href="{{ route('siswa.notifications') }}">
                    Lihat semua
                    <svg class="h-3.5 w-3.5 fill-none stroke-current transition-transform duration-200 group-hover:translate-x-0.5" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($alerts as $alert)
                    <div class="px-5 py-3.5 {{ $alert->dibaca_pada ? 'opacity-60' : '' }}">
                        <strong class="flex items-center gap-2 text-xs font-bold text-slate-800">
                            <i class="h-4 w-4 {{ $alert->level === 'berat' ? 'text-rose-600' : ($alert->level === 'sedang' ? 'text-orange-500' : 'text-blue-500') }}" data-lucide="{{ $alert->level === 'berat' ? 'circle-alert' : ($alert->level === 'sedang' ? 'triangle-alert' : 'info') }}"></i>
                            {{ $alert->judul }}
                        </strong>
                        <p class="mt-1 text-[0.78rem] leading-relaxed text-slate-500">{{ $alert->pesan }}</p>
                        <small class="mt-1 block text-[0.68rem] font-medium text-slate-400">{{ $alert->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <div class="grid min-h-36 place-items-center px-5 py-8 text-center">
                        <div>
                            <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-full bg-emerald-50 text-emerald-600">
                                <i data-lucide="bell" class="h-5 w-5"></i>
                            </span>
                            <strong class="block text-xs font-bold text-slate-700">Tidak ada notifikasi</strong>
                            <p class="mt-1 text-[0.68rem] font-medium text-slate-400">Peringatan dan tindak lanjut akan tampil di sini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-layouts.app>
