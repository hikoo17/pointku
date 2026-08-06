@php($title = 'Detail Poin')

<x-layouts.app :title="$title" >
    <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a] mb-[1.2rem]" href="{{ route('siswa.history') }}">
        <i data-lucide="eye" class="grid grid-cols-1 gap-[1rem] min-[461px]:grid-cols-[minmax(280px,.8fr)_minmax(0,1.2fr)]"></i>
                        Lihat bukti
                    </a>
                @endif
            </div>
        </section>
    </div>
</x-layouts.app>
