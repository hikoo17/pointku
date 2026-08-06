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
                    <i data-lucide="check" class="mb-[.3rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]"></i>
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
