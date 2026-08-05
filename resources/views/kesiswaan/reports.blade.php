@php($title='Laporan Masuk')

<x-layouts.app :title="$title">
    <x-dashboard
        title="Tinjau laporan"
        eyebrow="KEPUTUSAN KESISWAAN"
        copy="Setujui, tolak, atau kembalikan laporan untuk memastikan tindak lanjut yang tepat."
    />

    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">Pengaju</th>
                        <th class="px-5 py-3">Tindakan</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-center">Keputusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($reports as $report)
                        <tr class="transition hover:bg-slate-50/80">
                            {{-- Siswa --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#5c1919] font-bold text-white shadow-2xs">
                                        {{ strtoupper(substr($report->siswa->user->nama_lengkap ?? 'S', 0, 1)) }}
                                    </span>
                                    <span class="truncate font-bold text-slate-900">
                                        {{ $report->siswa->user->nama_lengkap }}
                                    </span>
                                </div>
                            </td>

                            {{-- Pengaju --}}
                            <td class="px-5 py-3.5 font-medium text-slate-600">
                                {{ $report->bk->nama_lengkap }}
                            </td>

                            {{-- Tindakan --}}
                            <td class="px-5 py-3.5 font-medium text-slate-600">
                                {{ $report->jenis_tindakan }}
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-[0.68rem] font-semibold capitalize text-amber-700 border border-amber-200/60">
                                    {{ $report->status }}
                                </span>
                            </td>

                            {{-- Form Keputusan --}}
                            <td class="px-5 py-3.5 text-center">
                                <form method="POST" action="{{ route('kesiswaan.reports.approval', $report) }}" class="inline-flex items-center justify-center gap-1.5">
                                    @csrf
                                    <select name="status" class="h-8 rounded-lg border border-slate-200 bg-slate-50 px-2.5 text-xs font-semibold text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white">
                                        <option value="disetujui">Setujui</option>
                                        <option value="ditolak">Tolak</option>
                                        <option value="pending">Revisi</option>
                                    </select>
                                    <button type="submit" class="group flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900" title="Simpan Keputusan">
                                        <svg class="h-4 w-4 fill-none stroke-current" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400 mb-2">
                                    <svg class="h-5 w-5 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium">Belum ada laporan masuk.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="border-t border-slate-100 px-5 py-3 bg-slate-50/50">
                {{ $reports->links() }}
            </div>
        @endif
    </section>
</x-layouts.app>