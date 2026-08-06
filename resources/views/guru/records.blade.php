@php
    $title = 'Catatan Poin';
@endphp

<x-layouts.app :title="$title">
    <x-dashboard
        title="Catatan poin"
        eyebrow="KEJADIAN SISWA"
        copy="Catat pelanggaran atau apresiasi dengan kronologi yang dapat ditindaklanjuti."
    />

    {{-- Form Pencatatan --}}
    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="border-b border-slate-100 px-5 py-4">
            <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">FORM KEJADIAN</span>
            <h3 class="text-base font-bold text-slate-900">Catat poin baru</h3>
        </div>
        <form method="POST" action="{{ route('guru.records.store') }}" enctype="multipart/form-data" class="p-5">
            @csrf
            <div class="grid grid-cols-1 gap-4 min-[461px]:grid-cols-2">
                <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                    Siswa
                    <select name="siswa_id" required class="min-w-0 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50">
                        <option value="">Pilih siswa</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->user->nama_lengkap }} · {{ $student->nisn }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                    Kategori
                    <select name="kategori_poin_id" required class="min-w-0 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50">
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ ucfirst($category->jenis) }} · {{ $category->nama_kategori }} ({{ $category->bobot_poin }})
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                    Tanggal
                    <input type="date" name="tanggal" value="{{ now()->format('Y-m-d') }}" required class="min-w-0 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50">
                </label>
                <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                    Bukti foto
                    <input type="file" name="bukti_foto" accept="image/*" class="min-w-0 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50">
                </label>
            </div>

            <label class="mt-4 grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                Kronologi
                <textarea name="keterangan" required placeholder="Jelaskan kejadian secara objektif" class="min-h-[120px] min-w-0 resize-y rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50"></textarea>
            </label>

            <div class="mt-5 flex justify-end">
                <button class="inline-flex items-center gap-1.5 rounded-lg bg-[#5c1919] px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#4a1414]" type="submit">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Simpan catatan
                </button>
            </div>
        </form>
    </section>

    {{-- Daftar Catatan --}}
    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 min-[641px]:flex-row min-[641px]:items-center min-[641px]:justify-between">
            <div>
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">DAFTAR KEJADIAN</span>
                <h3 class="text-base font-bold text-slate-900">Catatan poin siswa</h3>
            </div>
            <form method="GET" action="{{ route('guru.records') }}" class="flex items-center gap-2">
                <div class="relative">
                    <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama siswa..." class="w-full rounded-lg border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50 min-[641px]:w-64">
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @if($records->isNotEmpty())
                    @foreach($records as $record)
                        <tr class="transition hover:bg-slate-50/80">
                            {{-- Siswa --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#5c1919] font-bold text-white shadow-2xs">
                                        {{ strtoupper(substr($record->siswa->user->nama_lengkap ?? 'S', 0, 1)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <strong class="block truncate text-xs font-bold text-slate-800">{{ $record->siswa->user->nama_lengkap }}</strong>
                                        <small class="block text-[0.68rem] font-medium text-slate-400">{{ $record->siswa->nisn ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-5 py-3.5 font-medium text-slate-600">
                                {{ $record->kategoriPoin->nama_kategori }}
                                <small class="block text-[0.68rem] capitalize text-slate-400">{{ $record->kategoriPoin->jenis }}</small>
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-5 py-3.5 font-medium text-slate-600">
                                {{ $record->tanggal->format('d/m/Y') }}
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-5 py-3.5">
                                @php
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
                                <span class="inline-flex items-center rounded-md border px-2.5 py-1 text-[0.68rem] font-semibold capitalize {{ $statusStyles[$record->status_validasi] ?? 'bg-slate-100 text-slate-600 border-slate-200/60' }}">
                                    {{ $statusLabel[$record->status_validasi] ?? $record->status_validasi }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-2">
                                    <a class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900" href="{{ route('guru.records.show', $record) }}">
                                        <i data-lucide="eye" class="h-3.5 w-3.5 fill-none stroke-current"></i>
                                        Detail
                                    </a>
                                    @if(auth()->user()->hasRole('Guru BK') && $record->status_validasi === 'menunggu_validasi')
                                        <form method="POST" action="{{ route('guru.records.validate', $record) }}" class="inline-flex items-center gap-1.5">
                                            @csrf
                                            <button class="inline-flex items-center gap-1.5 rounded-lg bg-[#5c1919] px-3 py-1.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#4a1414]" name="status_validasi" value="disetujui" data-confirm="Catatan poin ini akan disetujui." data-confirm-title="Setujui catatan?" data-confirm-button="Ya, setujui">
                                                <i data-lucide="check" class="h-3.5 w-3.5 fill-none stroke-current"></i>
                                                Setujui
                                            </button>
                                            <button class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50" name="status_validasi" value="ditolak" data-confirm="Catatan poin ini akan ditolak." data-confirm-title="Tolak catatan?" data-confirm-button="Ya, tolak">
                                                <i data-lucide="x" class="h-3.5 w-3.5 fill-none stroke-current"></i>
                                                Tolak
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                    <i data-lucide="notebook-pen" class="h-5 w-5 fill-none stroke-current"></i>
                                </div>
                                <span class="text-xs font-medium">Belum ada catatan.</span>
                            </td>
                        </tr>
                    @endif
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
