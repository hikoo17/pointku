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
                    <i data-lucide="check" class="h-5 w-5"></i>
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
