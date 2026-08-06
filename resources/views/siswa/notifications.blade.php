@php($title = 'Notifikasi')

<x-layouts.app :title="$title" >
    <x-dashboard
        title="Notifikasi dan tindak lanjut"
        eyebrow="PEMANTAUAN PRIBADI"
        copy="Informasi threshold dan tindak lanjut yang dapat kamu lihat."
    />

    <div class="grid gap-4">
        @forelse($notifications as $notification)
            <article class="flex items-start gap-4 rounded-xl border border-slate-200/80 border-l-[3px] p-5 shadow-xs transition hover:shadow-md {{ $notification->dibaca_pada ? 'border-l-slate-300 bg-white' : 'border-l-[#5c1919] bg-white' }}">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-lg {{ $notification->level === 'berat' ? 'bg-rose-50 text-rose-600' : ($notification->level === 'sedang' ? 'bg-orange-50 text-orange-500' : 'bg-blue-50 text-blue-500') }}">
                    <i data-lucide="{{ $notification->level === 'berat' ? 'circle-alert' : ($notification->level === 'sedang' ? 'triangle-alert' : 'info') }}" class="h-5 w-5"></i>
                </span>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <strong class="text-sm font-bold text-slate-800">{{ $notification->judul }}</strong>
                        @if($notification->dibaca_pada)
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[0.65rem] font-semibold text-slate-500">Dibaca</span>
                        @endif
                    </div>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ $notification->pesan }}</p>
                    <small class="mt-1.5 block text-[0.68rem] font-medium text-slate-400">{{ $notification->created_at->diffForHumans() }}</small>
                </div>

                @unless($notification->dibaca_pada)
                    <form method="POST" action="{{ route('siswa.notifications.read', $notification) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900">
                            <i data-lucide="check" class="h-4 w-4"></i>
                            Tandai dibaca
                        </button>
                    </form>
                @endunless
            </article>
        @empty
            <div class="flex min-h-[420px] flex-col items-center justify-center gap-2 text-center text-slate-400">
                <span class="mx-auto mb-2 grid h-12 w-12 place-items-center rounded-xl bg-slate-100 text-slate-400">
                    <i data-lucide="bell" class="h-6 w-6"></i>
                </span>
                <strong class="text-sm font-bold text-slate-700">Belum ada notifikasi</strong>
                <p class="max-w-[400px] text-xs font-medium">Sistem akan menampilkan informasi saat threshold tertentu tercapai.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-5">
            {{ $notifications->links() }}
        </div>
    @endif
</x-layouts.app>
