@php($title='Surat Panggilan')

<x-layouts.app :title="$title">
    <x-dashboard
        title="Surat panggilan"
        eyebrow="TINDAK LANJUT BK"
        copy="Pantau surat yang dibuat otomatis setelah siswa melewati threshold."
    />

    <form method="GET" action="{{ route('guru.letters') }}" class="mt-6 grid grid-cols-1 gap-3 rounded-xl border border-slate-200/80 bg-white p-4 shadow-xs min-[761px]:grid-cols-[minmax(180px,1fr)_minmax(150px,.7fr)_minmax(140px,.6fr)_minmax(140px,.6fr)_auto_auto] min-[761px]:items-end">
        <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
            Cari surat atau siswa
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Nomor surat / nama siswa" class="h-9 rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-800 outline-none focus:border-slate-400">
        </label>
        <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
            Status
                <select name="status" class="h-9 rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-800 outline-none focus:border-slate-400">
                    <option value="">Semua status</option>
                    @foreach(['draft' => 'Draft', 'diajukan' => 'Diajukan', 'perlu_revisi' => 'Perlu revisi', 'disetujui' => 'Disetujui', 'dicetak' => 'Dicetak', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $value => $label)
                        @if(in_array($value, $statuses ?? []))
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endif
                    @endforeach
                </select>
        </label>
        <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
            Dari tanggal
            <input type="date" name="dari" value="{{ request('dari') }}" class="h-9 rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-800 outline-none focus:border-slate-400">
        </label>
        <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
            Sampai tanggal
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="h-9 rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-800 outline-none focus:border-slate-400">
        </label>
        <button type="submit" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-[#5c1919] px-4 text-xs font-semibold text-white transition hover:bg-[#4a1414]">
            <i data-lucide="filter" class="h-3.5 w-3.5"></i>
            Terapkan
        </button>
        @if(request()->hasAny(['q', 'status', 'dari', 'sampai']))
            <a href="{{ route('guru.letters') }}" class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">Reset</a>
        @endif
    </form>

    <section class="mt-4 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Nomor Surat</th>
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Poin</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($letters as $letter)
                        <tr class="transition hover:bg-slate-50/80">
                            {{-- Nomor Surat (Plain Text) --}}
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                {{ $letter->nomor_surat ?? 'Draf #'.$letter->id }}
                            </td>

                            {{-- Siswa --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#5c1919] font-bold text-white shadow-2xs">
                                        {{ strtoupper(substr($letter->siswa->user->nama_lengkap ?? 'S', 0, 1)) }}
                                    </span>
                                    <span class="truncate font-bold text-slate-900">
                                        {{ $letter->siswa->user->nama_lengkap }}
                                    </span>
                                </div>
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-5 py-3.5 font-medium text-slate-600">
                                {{ $letter->tanggal_surat?->format('d/m/Y') ?? '-' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-[0.68rem] font-semibold capitalize text-amber-700 border border-amber-200/60">
                                    {{ $letter->status }}
                                </span>
                            </td>

                            {{-- Total Poin --}}
                            <td class="px-5 py-3.5 font-bold text-rose-600">
                                {{ $letter->total_poin }} Poin
                            </td>

                            {{-- Tombol Detail --}}
                            <td class="px-5 py-3.5 text-center">
                                <a class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900" href="{{ route('guru.letters.show', $letter) }}">
                                    Detail
                                    <svg class="h-3 w-3 fill-none stroke-current transition-transform duration-200 group-hover:translate-x-0.5" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400 mb-2">
                                    <svg class="h-5 w-5 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium">Belum ada surat panggilan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($letters->hasPages())
            <div class="border-t border-slate-100 px-5 py-3 bg-slate-50/50">
                {{ $letters->links() }}
            </div>
        @endif
    </section>
</x-layouts.app>
