@php
    $title = 'Dashboard ' . auth()->user()->role->nama_role;
@endphp

<x-layouts.app :title="$title">
    <x-dashboard
        title="Ruang kerja Guru BK"
        eyebrow="VALIDASI DAN PENDAMPINGAN"
        copy="Periksa catatan kejadian, pantau saldo siswa, lalu kirim penanganan resmi saat threshold terlewati."
    />

    {{-- Stats Grid --}}
    <div class="mb-6 grid grid-cols-2 gap-4 min-[1051px]:grid-cols-4">
        @php
            $stats = [
                ['label' => 'Siswa terdata', 'value' => $studentCount ?? 0, 'icon' => 'users', 'textColor' => 'text-[#6d1a1a]', 'badgeBg' => 'bg-[#6d1a1a]/5 border-[#6d1a1a]/10', 'borderTop' => 'border-t-[#6d1a1a]', 'href' => null],
                ['label' => 'Menunggu validasi', 'value' => $pendingCount ?? 0, 'icon' => 'clock-3', 'textColor' => 'text-[#f57f17]', 'badgeBg' => 'bg-[#f57f17]/5 border-[#f57f17]/10', 'borderTop' => 'border-t-[#fbc02d]', 'href' => route('guru.records')],
                ['label' => 'Laporan pending', 'value' => $reportCount ?? 0, 'icon' => 'file', 'textColor' => 'text-[#b71c1c]', 'badgeBg' => 'bg-[#b71c1c]/5 border-[#b71c1c]/10', 'borderTop' => 'border-t-[#b71c1c]', 'href' => route('guru.reports')],
                ['label' => 'Surat otomatis', 'value' => $letterCount ?? 0, 'icon' => 'letter', 'textColor' => 'text-[#f9a825]', 'badgeBg' => 'bg-[#f9a825]/5 border-[#f9a825]/10', 'borderTop' => 'border-t-[#fbc02d]', 'href' => null],
            ];
        @endphp

        @foreach($stats as $stat)
            <article class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200/80 border-t-[3px] {{ $stat['borderTop'] }} bg-white p-4.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md min-[761px]:p-5">
                @if($stat['href'])
                    <a class="absolute inset-0 z-10" href="{{ $stat['href'] }}" aria-label="Lihat {{ strtolower($stat['label']) }}"></a>
                @endif
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">{{ $stat['label'] }}</span>
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border {{ $stat['badgeBg'] }} {{ $stat['textColor'] }}">
                        <i data-lucide="{{ $stat['icon'] }}" class="h-4 w-4"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <strong class="block text-3xl font-bold tracking-tight text-slate-900">{{ $stat['value'] }}</strong>
                    <small class="mt-0.5 flex items-center gap-1 text-[0.65rem] font-medium text-slate-400">
                        Dalam sistem
                        @if($stat['href'])
                            <span class="font-bold text-slate-500">Lihat detail</span>
                        @endif
                    </small>
                </div>
            </article>
        @endforeach
    </div>

    {{-- Content Grid --}}
    <div class="mt-6 grid grid-cols-1 gap-6 min-[761px]:grid-cols-2">
        <section class="flex flex-col overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
                <div>
                    <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">VALIDASI UTAMA</span>
                    <h3 class="text-base font-bold text-slate-900">Catatan menunggu pemeriksaan</h3>
                </div>
                <a class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900" href="{{ route('guru.records') }}">
                    Buka semua
                    <i data-lucide="arrow-right" class="h-3.5 w-3.5 fill-none stroke-current transition-transform duration-200 group-hover:translate-x-0.5"></i>
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($records->where('status_validasi', 'menunggu_validasi') as $record)
                    <div class="flex items-center gap-3.5 px-5 py-3.5">
                        <span class="inline-block h-2.5 w-2.5 shrink-0 rounded-full {{ optional($record->kategoriPoin)->jenis === 'pelanggaran' ? 'bg-rose-600' : 'bg-amber-500' }}"></span>
                        <div class="min-w-0 flex-1">
                            <strong class="block truncate text-xs font-bold text-slate-800">{{ $record->siswa->user->nama_lengkap ?? 'Tanpa Nama' }}</strong>
                            <small class="mt-0.5 block text-[0.68rem] font-medium text-slate-500">
                                {{ $record->kategoriPoin->nama_kategori ?? '-' }} • {{ optional($record->tanggal)->format('d/m/Y') }}
                            </small>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <a class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900" href="{{ route('guru.records.show', $record) }}">
                                <i data-lucide="eye" class="h-3.5 w-3.5 fill-none stroke-current"></i>
                                Detail
                            </a>
                            <form method="POST" action="{{ route('guru.records.validate', $record) }}" class="flex items-center gap-2">
                                @csrf
                                <button type="submit" class="inline-flex justify-center items-center gap-1.5 rounded-lg bg-[#5c1919] px-3 py-1.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#4a1414]" name="status_validasi" value="disetujui" data-confirm="Catatan poin ini akan disetujui." data-confirm-title="Setujui catatan?" data-confirm-button="Ya, setujui">Setujui</button>
                                <button type="submit" class="inline-flex justify-center items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50" name="status_validasi" value="ditolak" data-confirm="Catatan poin ini akan ditolak." data-confirm-title="Tolak catatan?" data-confirm-button="Ya, tolak">Tolak</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="grid min-h-36 place-items-center px-5 py-8 text-center">
                        <div>
                            <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-full bg-emerald-50 text-emerald-600">
                                <i data-lucide="check" class="h-5 w-5"></i>
                            </span>
                            <strong class="block text-xs font-bold text-slate-700">Tidak ada catatan yang menunggu validasi</strong>
                            <p class="mt-1 text-[0.68rem] font-medium text-slate-400">Semua catatan telah ditinjau.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="flex flex-col overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
            <div class="border-b border-slate-100 px-5 py-4">
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">ALUR BK</span>
                <h3 class="text-base font-bold text-slate-900">Catat, validasi, dan tindak lanjuti.</h3>
            </div>
            <div class="p-5">
                <p class="mb-4 text-sm leading-relaxed text-slate-500">Setelah catatan disetujui, Guru BK dapat mengirim laporan ke Kesiswaan untuk keputusan resmi.</p>
                <a class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900" href="{{ route('guru.reports') }}">
                    Kelola laporan
                    <i data-lucide="arrow-right" class="h-3.5 w-3.5 fill-none stroke-current transition-transform duration-200 group-hover:translate-x-0.5"></i>
                </a>
            </div>
        </section>
    </div>
</x-layouts.app>