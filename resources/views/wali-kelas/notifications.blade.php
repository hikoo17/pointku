@php($title = 'Notifikasi Kelas')

<x-layouts.app :title="$title">
    <x-dashboard
        title="Peringatan kelas {{ $kelas->nama_kelas }}"
        eyebrow="THRESHOLD SISWA"
        copy="Pantau siswa yang mencapai ambang penanganan dan status tindak lanjutnya."
    />

    <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $notification)
                <div class="flex items-center gap-4 px-5 py-4">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg border {{ $notification->level === 'berat' ? 'bg-rose-50 text-rose-600 border-rose-100' : ($notification->level === 'sedang' ? 'bg-orange-50 text-orange-500 border-orange-100' : 'bg-blue-50 text-blue-500 border-blue-100') }}">
                        <i data-lucide="{{ $notification->level === 'berat' ? 'circle-alert' : ($notification->level === 'sedang' ? 'triangle-alert' : 'info') }}" class="h-5 w-5"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <strong class="truncate text-sm font-bold text-slate-800">{{ $notification->siswa->user->nama_lengkap }}</strong>
                            @if($notification->dibaca_pada)
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[0.65rem] font-semibold text-slate-600 border border-slate-200/60">Dibaca</span>
                            @endif
                        </div>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ $notification->judul }}</p>
                        <small class="mt-1 block text-[0.68rem] font-medium text-slate-400">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    @unless($notification->dibaca_pada)
                        <form method="POST" action="{{ route('wali-kelas.notifications.read', $notification) }}" class="shrink-0">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900">
                                <i data-lucide="check" class="h-3.5 w-3.5 fill-none stroke-current"></i>
                                Tandai dibaca
                            </button>
                        </form>
                    @endunless
                </div>
            @empty
                <div class="grid min-h-96 place-items-center px-5 py-8 text-center">
                    <div>
                        <span class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-full bg-emerald-50 text-emerald-600">
                            <i data-lucide="bell" class="h-5 w-5"></i>
                        </span>
                        <strong class="block text-xs font-bold text-slate-700">Belum ada notifikasi kelas</strong>
                        <p class="mt-1 text-[0.68rem] font-medium text-slate-400">Peringatan akan muncul saat siswa mencapai threshold.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="border-t border-slate-100 px-5 py-3 bg-slate-50/50">
                {{ $notifications->links() }}
            </div>
        @endif
    </section>
</x-layouts.app>
