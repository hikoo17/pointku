@php($title = 'Riwayat Poin')

<x-layouts.app :title="$title" >
    <x-dashboard
        title="Riwayat poin saya"
        eyebrow="CATATAN PRIBADI"
        copy="Seluruh pelanggaran dan apresiasi yang telah divalidasi Guru BK."
    />

    <section class="overflow-hidden rounded-lg border border-[#fce4c4] bg-white shadow-sm">
        <form method="GET" class="flex flex-wrap items-end gap-4 p-5">
            <label class="min-w-[150px] flex-1 text-xs font-bold text-[#5d4037]">
                Jenis
                <select name="jenis" class="mt-1 min-w-0 flex-1 rounded-xl border border-[#fce4c4] bg-white px-4 py-3 text-sm text-[#4a1c1c] outline-none focus:border-[#6d1a1a] transition">
                    <option value="">Semua</option>
                    <option value="pelanggaran" @selected(request('jenis') === 'pelanggaran')>Pelanggaran</option>
                    <option value="apresiasi" @selected(request('jenis') === 'apresiasi')>Apresiasi</option>
                </select>
            </label>
            <label class="min-w-[150px] flex-1 text-xs font-bold text-[#5d4037]">
                Dari
                <input type="date" name="dari" value="{{ request('dari') }}" class="mt-1 min-w-0 flex-1 rounded-xl border border-[#fce4c4] bg-white px-4 py-3 text-sm text-[#4a1c1c] outline-none focus:border-[#6d1a1a] transition">
            </label>
            <label class="min-w-[150px] flex-1 text-xs font-bold text-[#5d4037]">
                Sampai
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="mt-1 min-w-0 flex-1 rounded-xl border border-[#fce4c4] bg-white px-4 py-3 text-sm text-[#4a1c1c] outline-none focus:border-[#6d1a1a] transition">
            </label>
            <button class="inline-flex justify-center items-center gap-2 min-h-[42px] rounded-xl border-0 bg-[#6d1a1a] px-5 py-2.5 text-sm font-bold text-white shadow-md hover:shadow-lg hover:-translate-y-px hover:bg-[#5a1515] transition" type="submit">
                <svg class="h-4 w-4">
                    <use href="#icon-search"></use>
                </svg>
                Terapkan
            </button>
            <a class="inline-flex justify-center items-center gap-2 min-h-[42px] rounded-xl border border-[#fce4c4] bg-white px-5 py-2.5 text-sm font-bold text-[#5d4037] shadow-sm hover:bg-[#fff8e1] transition" href="{{ route('siswa.history') }}">Reset</a>
        </form>
    </section>

    <section class="overflow-hidden rounded-lg border border-[#fce4c4] bg-white shadow-sm mt-5">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-separate min-w-[760px]">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#8d6e63] bg-[#fff8e1]">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#8d6e63] bg-[#fff8e1]">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#8d6e63] bg-[#fff8e1]">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#8d6e63] bg-[#fff8e1]">Pencatat</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#8d6e63] bg-[#fff8e1]">Poin</th>
                        <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider text-[#8d6e63] bg-[#fff8e1]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td class="px-6 py-4 text-sm text-[#5d4037] border-t border-[#fff3e0]">{{ $record->tanggal->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 border-t border-[#fff3e0]">
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-extrabold capitalize {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-[#5d4037] bg-[#fff9c4]' : 'text-[#c62828] bg-[#ffebee]' }}">
                                    {{ ucfirst($record->kategoriPoin->jenis) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#5d4037] border-t border-[#fff3e0]">{{ $record->kategoriPoin->nama_kategori }}</td>
                            <td class="px-6 py-4 border-t border-[#fff3e0]">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-[#6d1a1a] font-extrabold text-white text-sm">{{ substr($record->pencatat->nama_lengkap,0,1) }}</span>
                                    <span class="text-sm">{{ $record->pencatat->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 border-t border-[#fff3e0] {{ $record->kategoriPoin->jenis === 'apresiasi' ? 'text-[#5d4037]' : 'text-[#c62828]' }}">
                                <strong class="text-sm">{{ $record->kategoriPoin->jenis === 'apresiasi' ? '+' : '-' }}{{ $record->kategoriPoin->bobot_poin }}</strong>
                            </td>
                            <td class="px-6 py-4 text-center border-t border-[#fff3e0]">
                                <a class="inline-flex items-center gap-1 text-sm font-extrabold text-[#6d1a1a] hover:underline" href="{{ route('siswa.history.show', $record) }}">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="h-40 text-center text-[#a1887f] border-t border-[#fff3e0]">
                                <span class="mx-auto mb-3 grid h-11 w-11 place-items-center rounded-xl bg-[#fff3e0] text-[#6d1a1a]">
                                    <svg class="h-5 w-5">
                                        <use href="#icon-clock"></use>
                                    </svg>
                                </span>
                                <p class="text-sm">Belum ada riwayat untuk filter ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $records->links() }}
    </section>
</x-layouts.app>
