@php($title = 'Notifikasi Kelas')

<x-layouts.app :title="$title" >
    <x-dashboard
        title="Peringatan kelas {{ $kelas->nama_kelas }}"
        eyebrow="THRESHOLD SISWA"
        copy="Pantau siswa yang mencapai ambang penanganan dan status tindak lanjutnya."
    />

    <div class="grid gap-[.8rem]">
        @forelse($notifications as $notification)
            <article class="grid grid-cols-[auto_1fr_auto] items-center gap-[1rem] rounded-[13px] border border-[#fce4c4] border-l-[3px] border-l-[#b71c1c] bg-white p-[1.25rem] {{ $notification->dibaca_pada ? 'opacity-[.72] border-l-[#f9a825]' : '' }}">
                <span class="grid h-[42px] w-[42px] place-items-center rounded-[12px] bg-[#ffebee] text-[#b71c1c]">
                    <svg>
                        <use href="#icon-{{ $notification->level === 'berat' ? 'alert' : ($notification->level === 'sedang' ? 'warning' : 'info') }}"></use>
                    </svg>
                </span>
                <div>
                    <p class="mb-[.3rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">{{ $notification->siswa->user->nama_lengkap }} Â· {{ $notification->aturanThreshold->poin_batas ?? '-' }} POIN</p>
                    <h3 class="mb-[.45rem] text-[.9rem] font-bold">{{ $notification->judul }}</h3>
                    <p class="mb-[.45rem] text-[.72rem] text-[#6d4c41]">{{ $notification->pesan }}</p>
                    <small class="text-[.6rem] text-[#a1887f]">
                        {{ $notification->created_at->translatedFormat('d F Y, H:i') }}
                        Â·
                        {{ $notification->is_resolved ? 'Sudah ditindaklanjuti' : 'Dalam pemantauan' }}
                    </small>
                </div>

                @unless($notification->dibaca_pada)
                    <form method="POST" action="{{ route('wali-kelas.notifications.read',$notification) }}">
                        @csrf
                        <button class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border-0 bg-[#6d1a1a] px-[1rem] py-[.72rem] text-[.75rem] font-[750] text-white shadow-[0_8px_20px_#6d1a1a38] transition hover:-translate-y-px hover:bg-[#5a1515]" type="submit">
                            <svg>
                                <use href="#icon-check"></use>
                            </svg>
                            Tandai dibaca
                        </button>
                    </form>
                @endunless
            </article>
        @empty
            <div class="flex min-h-[420px] flex-col items-center justify-center gap-[.55rem] text-center text-[#a1887f]">
                <span class="mx-auto mb-[.7rem] grid h-[42px] w-[42px] place-items-center rounded-[12px] bg-[#ffebee] text-[#b71c1c]">
                    <svg>
                        <use href="#icon-bell"></use>
                    </svg>
                </span>
                <strong class="text-[#4a1c1c]">Belum ada notifikasi kelas</strong>
                <p>Peringatan akan muncul saat siswa mencapai threshold.</p>
            </div>
        @endforelse
    </div>

    {{ $notifications->links() }}
</x-layouts.app>
