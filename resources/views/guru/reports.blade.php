@php
    $title = 'Laporan Kesiswaan';
@endphp

<x-layouts.app :title="$title">
    <x-dashboard
        title="Kirim laporan penanganan"
        eyebrow="ALUR TINDAK LANJUT"
        copy="Ajukan siswa yang membutuhkan keputusan resmi Kesiswaan."
    />

    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="border-b border-slate-100 px-5 py-4">
            <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">PENGAJUAN LAPORAN</span>
            <h3 class="text-base font-bold text-slate-900">Buat laporan baru</h3>
        </div>

        <form method="POST" action="{{ route('guru.reports.store') }}" class="p-5">
            @csrf
            <div class="grid grid-cols-1 gap-4 min-[641px]:grid-cols-2">
                <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                    Siswa
                    <select name="siswa_id" required class="min-w-0 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50">
                        <option value="">Pilih siswa</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" @selected(old('siswa_id') == $student->id)>
                                {{ $student->user->nama_lengkap }} &middot; {{ $student->total_poin_pelanggaran }} poin
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                    Jenis tindakan
                    <input name="jenis_tindakan" value="{{ old('jenis_tindakan') }}" required placeholder="Contoh: Pemanggilan orang tua" class="min-w-0 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50">
                </label>
            </div>

            <div class="mt-5 flex justify-end">
                <button class="inline-flex items-center gap-1.5 rounded-lg bg-[#5c1919] px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#4a1414]" type="submit">
                    <i data-lucide="send" class="h-4 w-4"></i>
                    Kirim laporan
                </button>
            </div>
        </form>
    </section>

    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="border-b border-slate-100 px-5 py-4">
            <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">RIWAYAT PENGAJUAN</span>
            <h3 class="text-base font-bold text-slate-900">Laporan saya</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">Tindakan</th>
                        <th class="px-5 py-3">Tanggal Pengajuan</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @if($reports->isNotEmpty())
                        @foreach($reports as $report)
                            @php
                                $statusStyles = [
                                    'disetujui' => 'border-emerald-200/60 bg-emerald-50 text-emerald-700',
                                    'ditolak' => 'border-rose-200/60 bg-rose-50 text-rose-700',
                                    'pending' => 'border-amber-200/60 bg-amber-50 text-amber-700',
                                ];
                                $statusLabels = [
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'pending' => 'Pending',
                                ];
                            @endphp
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#5c1919] font-bold text-white shadow-2xs">
                                            {{ strtoupper(substr($report->siswa->user->nama_lengkap ?? 'S', 0, 1)) }}
                                        </span>
                                        <span class="truncate font-bold text-slate-900">{{ $report->siswa->user->nama_lengkap }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-medium text-slate-600">{{ $report->jenis_tindakan }}</td>
                                <td class="px-5 py-3.5 font-medium text-slate-600">
                                    {{ optional($report->diajukan_pada)->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center rounded-md border px-2.5 py-1 text-[0.68rem] font-semibold {{ $statusStyles[$report->status] ?? 'border-slate-200/60 bg-slate-100 text-slate-600' }}">
                                        {{ $statusLabels[$report->status] ?? ucfirst($report->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                    <i data-lucide="file-text" class="h-5 w-5"></i>
                                </div>
                                <span class="text-xs font-medium">Belum ada laporan.</span>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="border-t border-slate-100 bg-slate-50/50 px-5 py-3">
                {{ $reports->links() }}
            </div>
        @endif
    </section>
</x-layouts.app>
