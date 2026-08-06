@php($title = 'Riwayat Poin')

<x-layouts.app :title="$title" >
    <x-dashboard
        title="Riwayat poin saya"
        eyebrow="CATATAN PRIBADI"
        copy="Seluruh pelanggaran dan apresiasi yang telah divalidasi Guru BK."
    />

    <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <form method="GET" class="flex flex-wrap items-end gap-4 p-5">
            <label class="min-w-[150px] flex-1 text-xs font-semibold text-slate-600">
                Jenis
                <select name="jenis" class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-slate-50">
                    <option value="">Semua</option>
                    <option value="pelanggaran" @selected(request('jenis') === 'pelanggaran')>Pelanggaran</option>
                    <option value="apresiasi" @selected(request('jenis') === 'apresiasi')>Apresiasi</option>
                </select>
            </label>
            <label class="min-w-[150px] flex-1 text-xs font-semibold text-slate-600">
                Dari
                <input type="date" name="dari" value="{{ request('dari') }}" class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-slate-50">
            </label>
            <label class="min-w-[150px] flex-1 text-xs font-semibold text-slate-600">
                Sampai
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-slate-50">
            </label>
            <button class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#5c1919] px-5 py-2.5 text-sm font-semibold text-white shadow-2xs transition hover:-translate-y-px hover:bg-[#4a1313]" type="submit">
                <i data-lucide="search" class="h-4 w-4"></i>
                Terapkan
            </button>
            <a class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900" href="{{ route('siswa.history') }}">Reset</a>
        </form>
    </section>

    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Jenis</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Pencatat</th>
                        <th class="px-5 py-3">Poin</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($records as $record)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-5 py-3.5 text-sm text-slate-700">{{ $record->tanggal->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 rounded-md px-2.5 py-1 text-[0.68rem] font-semibold capitalize {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-emerald-700 bg-emerald-50 border border-emerald-200/60' : 'text-rose-700 bg-rose-50 border border-rose-200/60' }}">
                                    {{ ucfirst($record->kategoriPoin->jenis) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-sm text-slate-700">{{ $record->kategoriPoin->nama_kategori }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-[#6d1a1a] text-xs font-extrabold text-white">{{ substr($record->pencatat->nama_lengkap,0,1) }}</span>
                                    <span class="text-sm text-slate-700">{{ $record->pencatat->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-emerald-600' : 'text-rose-600' }}">
                                <strong class="text-sm">{{ $record->kategoriPoin->jenis === 'apresiasi' ? '+' : '-' }}{{ $record->kategoriPoin->bobot_poin }}</strong>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <a class="inline-flex items-center gap-1 text-sm font-semibold text-[#5c1919] hover:underline" href="{{ route('siswa.history.show', $record) }}">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                    <i data-lucide="clock-3" class="h-5 w-5"></i>
                                </div>
                                <span class="text-xs font-medium">Belum ada riwayat untuk filter ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
            <div class="border-t border-slate-100 px-5 py-3 bg-slate-50/50">
                {{ $records->links() }}
            </div>
        @endif
    </section>
</x-layouts.app>
