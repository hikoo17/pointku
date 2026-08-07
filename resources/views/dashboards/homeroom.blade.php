@php($title = 'Dashboard Wali Kelas')

<x-layouts.app :title="$title">
    <x-dashboard
        title="Kelas {{ $kelas->nama_kelas }}"
        eyebrow="RUANG WALI KELAS"
        copy="Pantau perkembangan seluruh siswa di kelas tanpa mengubah transaksi poin."
    />

    {{-- Stats Grid --}}
    <div class="mb-6 grid grid-cols-2 gap-4 min-[1051px]:grid-cols-4">
        @foreach([
            ['Total Siswa', $students->count(), 'users', 'text-slate-700', 'bg-slate-100 border-slate-200/60', 'border-t-slate-400', null],
            ['Perlu Dipantau', $students->where('total_poin_pelanggaran', '>=', 25)->count(), 'circle-alert', 'text-rose-700', 'bg-rose-50 border-rose-100', 'border-t-rose-600', route('wali-kelas.students', ['status' => 'dipantau'])],
            ['Poin Apresiasi', $students->sum('total_poin_apresiasi'), 'heart', 'text-amber-700', 'bg-amber-50 border-amber-100', 'border-t-amber-500', null],
            ['Poin Pelanggaran', $students->sum('total_poin_pelanggaran'), 'triangle-alert', 'text-[#5c1919]', 'bg-[#5c1919]/5 border-[#5c1919]/10', 'border-t-[#5c1919]', null],
        ] as [$label, $value, $icon, $textColor, $badgeBg, $borderTop, $href])
            <article class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200/80 border-t-[3px] {{ $borderTop }} bg-white p-4.5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md min-[761px]:p-5">
                @if($href)
                    <a class="absolute inset-0 z-10" href="{{ $href }}" aria-label="Lihat {{ strtolower($label) }}"></a>
                @endif
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">{{ $label }}</span>
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border {{ $badgeBg }} {{ $textColor }}">
                        <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <strong class="block text-3xl font-bold tracking-tight text-slate-900">{{ $value }}</strong>
                    <small class="mt-0.5 flex items-center gap-1 text-[0.65rem] font-medium text-slate-400">
                        @if($href)
                            <span class="font-bold text-slate-500">Lihat detail</span>
                        @endif
                    </small>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 min-[761px]:grid-cols-2">
        {{-- Siswa Perlu Dipantau --}}
        <section class="flex flex-col overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
                <div>
                    <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">PRIORITAS PENDAMPINGAN</span>
                    <h3 class="text-base font-bold text-slate-900">Siswa perlu dipantau</h3>
                </div>
                <a class="group hidden shrink-0 items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900 min-[761px]:inline-flex" href="{{ route('wali-kelas.students', ['status' => 'dipantau']) }}">
                    Lihat semua
                    <i data-lucide="arrow-right" class="h-3.5 w-3.5 fill-none stroke-current transition-transform duration-200 group-hover:translate-x-1"></i>
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($students->where('total_poin_pelanggaran', '>=', 25) as $student)
                    <a class="flex items-center gap-3 px-5 py-3.5 transition hover:bg-slate-50/80" href="{{ route('wali-kelas.students.show', $student) }}">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-[#6d1a1a] text-xs font-extrabold text-white">{{ substr($student->user->nama_lengkap, 0, 1) }}</span>
                        <div class="min-w-0 flex-1">
                            <strong class="block truncate text-xs font-bold text-slate-800">{{ $student->user->nama_lengkap }}</strong>
                            <small class="mt-0.5 block text-[0.68rem] font-medium text-slate-500">NISN {{ $student->nisn }}</small>
                        </div>
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[0.68rem] font-bold text-rose-700 bg-rose-50 border border-rose-100">{{ $student->total_poin_pelanggaran }} poin</span>
                    </a>
                @empty
                    <div class="grid min-h-36 place-items-center px-5 py-8 text-center">
                        <div>
                            <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-full bg-emerald-50 text-emerald-600">
                                <i data-lucide="shield-check" class="h-5 w-5"></i>
                            </span>
                            <strong class="block text-xs font-bold text-slate-700">Kondisi siswa terkendali</strong>
                            <p class="mt-1 text-[0.68rem] font-medium text-slate-400">Tidak ada siswa yang melewati threshold.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Peringatan Terbaru --}}
        <section class="flex flex-col overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
                <div>
                    <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">NOTIFIKASI KELAS</span>
                    <h3 class="text-base font-bold text-slate-900">Peringatan terbaru</h3>
                </div>
                <a class="group hidden shrink-0 items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900 min-[761px]:inline-flex" href="{{ route('wali-kelas.notifications') }}">
                    Lihat semua
                    <i data-lucide="arrow-right" class="h-3.5 w-3.5 fill-none stroke-current transition-transform duration-200 group-hover:translate-x-1"></i>
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($alerts as $alert)
                    <a class="block px-5 py-3.5 transition hover:bg-slate-50/80 {{ $alert->dibaca_pada ? 'opacity-65' : '' }}" href="{{ route('wali-kelas.notifications') }}">
                        <strong class="flex items-center gap-1.5 text-xs font-bold text-slate-800">
                            {{ $alert->siswa->user->nama_lengkap }} · {{ $alert->judul }}
                        </strong>
                        <p class="mt-1 text-[0.78rem] leading-relaxed text-slate-500">{{ $alert->pesan }}</p>
                        <small class="mt-1 block text-[0.68rem] font-medium text-slate-400">{{ $alert->created_at->diffForHumans() }}</small>
                    </a>
                @empty
                    <div class="grid min-h-36 place-items-center px-5 py-8 text-center">
                        <div>
                            <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-full bg-emerald-50 text-emerald-600">
                                <i data-lucide="bell" class="h-5 w-5"></i>
                            </span>
                            <strong class="block text-xs font-bold text-slate-700">Belum ada notifikasi</strong>
                            <p class="mt-1 text-[0.68rem] font-medium text-slate-400">Peringatan akan tampil saat siswa mencapai threshold.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Catatan Terbaru --}}
    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
            <div>
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">AKTIVITAS TERVALIDASI</span>
                <h3 class="text-base font-bold text-slate-900">Catatan terbaru kelas</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">Jenis</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3 text-right">Poin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentRecords as $record)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-[#6d1a1a] text-xs font-extrabold text-white">{{ substr($record->siswa->user->nama_lengkap, 0, 1) }}</span>
                                    <span class="font-medium text-slate-700">{{ $record->siswa->user->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[0.68rem] font-semibold capitalize {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-rose-50 text-rose-700 border border-rose-200/60' }}">
                                    {{ ucfirst($record->kategoriPoin->jenis) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-slate-600">{{ $record->kategoriPoin->nama_kategori }}</td>
                            <td class="px-5 py-3.5 font-medium text-slate-600">{{ $record->tanggal->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5 text-right font-bold {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $record->kategoriPoin->jenis === 'apresiasi' ? '+' : '-' }}{{ $record->kategoriPoin->bobot_poin }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <span class="text-xs font-medium">Belum ada aktivitas tervalidasi.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
