@php($title = 'Notifikasi Kelas')

<x-layouts.app :title="$title" >
    <x-dashboard
        title="Peringatan kelas {{ $kelas->nama_kelas }}"
        eyebrow="THRESHOLD SISWA"
        copy="Pantau siswa yang mencapai ambang penanganan dan status tindak lanjutnya."
    />

    <div class="grid gap-[.8rem]">
        @forelse($notifications as $notification)
            <article class="grid grid-cols-[auto_1fr_auto] items-center gap-[1rem] rounded-[13px] border border-[#fce4c4] border-l-[3px] p-[1.25rem] {{ $notification->dibaca_pada ? 'opacity-[.72] border-l-[#f9a825] bg-white' : 'border-l-[#b71c1c] bg-white' }}">
                <span class="grid h-[42px] w-[42px] place-items-center rounded-[12px] {{ $notification->level === 'berat' ? 'bg-rose-50 text-rose-600' : ($notification->level === 'sedang' ? 'bg-orange-50 text-orange-500' : 'bg-blue-50 text-blue-500') }}">
                    <i data-lucide="{{ $notification->level === 'berat' ? 'circle-alert' : ($notification->level === 'sedang' ? 'triangle-alert' : 'info') }}" class="h-5 w-5"></i>
                </span>

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <strong class="truncate text-sm font-bold text-[#4a1c1c]">{{ $notification->siswa->user->name ?? 'Siswa' }}</strong>
                        @if($notification->dibaca_pada)
                            <span class="rounded-full bg-[#fff8e1] px-2 py-0.5 text-[0.65rem] font-semibold text-[#6d1a1a]">Dibaca</span>
                        @endif
                    </div>
                    <p class="mt-1 text-xs leading-relaxed text-[#6d1a1a]/80">{{ $notification->judul }}</p>
                    <small class="mt-1.5 block text-[0.68rem] font-medium text-[#6d1a1a]/60">{{ $notification->created_at->diffForHumans() }}</small>
                </div>

                @unless($notification->dibaca_pada)
                    <form method="POST" action="{{ route('wali-kelas.notifications.read', $notification) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-[10px] border border-[#fce4c4] bg-[#fff8e1] px-3 py-1.5 text-xs font-semibold text-[#6d1a1a] transition hover:bg-[#ffe9c2]">
                            <i data-lucide="check" class="h-4 w-4"></i>
                            Tandai dibaca
                        </button>
                    </form>
                @endunless
            </article>
        @empty
            <div class="flex min-h-[420px] flex-col items-center justify-center gap-[.55rem] text-center text-[#a1887f]">
                <span class="mx-auto mb-[.7rem] grid h-[42px] w-[42px] place-items-center rounded-[12px] bg-[#ffebee] text-[#b71c1c]">
                    <i data-lucide="bell" class="h-6 w-6"></i>
                </span>
                <strong class="text-[#4a1c1c]">Belum ada notifikasi kelas</strong>
                <p>Peringatan akan muncul saat siswa mencapai threshold.</p>
            </div>
        @endforelse
    </div>

    {{ $notifications->links() }}
</x-layouts.app>
