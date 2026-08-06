@php($title = 'Detail Siswa')

<x-layouts.app :title="$title" >
    <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a] mb-[1.2rem]" href="{{ route('wali-kelas.students') }}">
        <i data-lucide="circle-alert" class="mb-[1.2rem] grid grid-cols-2 gap-[0.7rem] min-[761px]:gap-4 min-[1051px]:grid-cols-4"></i>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Pelanggaran</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $siswa->total_poin_pelanggaran }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">Poin tervalidasi</small>
        </article>

        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#f9a825] border-t-[3px] border-t-[#fbc02d66]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <i data-lucide="shield" class="block text-[.68rem] font-[750] text-[#8c6d6d]"></i>
            </span>
            <span class="block text-[.68rem] font-[750] text-[#8c6d6d]">Saldo</span>
            <strong class="my-[.7rem] mb-[.1rem] block text-[2.15rem] leading-none font-bold tracking-[-.06em] min-[761px]:text-[2.7rem]">{{ $siswa->saldo_poin }}</strong>
            <small class="text-[.62rem] text-[#8c6d6d]">Saldo poin siswa</small>
        </article>

        <article class="relative min-h-[135px] overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white p-4 shadow-[0_5px_18px_rgba(74,28,28,.03)] after:absolute after:-right-8 after:-bottom-11 after:h-[115px] after:w-[115px] after:rounded-full after:bg-current after:opacity-[.07] min-[761px]:min-h-[155px] min-[761px]:p-[1.35rem] text-[#f57f17] border-t-[3px] border-t-[#fbc02d]">
            <span class="absolute top-[0.9rem] right-[0.9rem] grid h-7 w-7 place-items-center rounded-[10px] bg-current opacity-[.85] text-white min-[761px]:top-[1.2rem] min-[761px]:right-[1.2rem] min-[761px]:h-8 min-[761px]:w-8">
                <i data-lucide="clock-3" class="block text-[.68rem] font-[750] text-[#8c6d6d]"></i>
                                    </span>
                                    Belum ada riwayat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $records->links() }}
        </section>

        <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
            <div class="flex items-center justify-between gap-4 border-b border-[#fce4c4] p-[1.1rem] min-[461px]:px-6 min-[461px]:py-[1.35rem]">
                <div>
                    <p class="mb-[.65rem] text-[.68rem] font-extrabold tracking-[.18em] text-[#6d1a1a]">PERINGATAN</p>
                    <h3 class="mb-[.25rem] text-[1.08rem] font-bold tracking-[-.025em]">Status peringatan</h3>
                </div>
            </div>

            @forelse($alerts as $alert)
                <div class="px-6 py-4 border-b border-[#fce4c4] {{ $alert->is_resolved ? 'opacity-[.65]' : '' }}">
                    <strong class="flex items-center gap-[.35rem]">
                        <i data-lucide="{{ $alert->level === 'berat' ? 'circle-alert' : ($alert->level === 'sedang' ? 'triangle-alert' : 'info') }}" class="h-4 w-4"></i>
                        {{ $alert->judul }}
                    </strong>
                    <p class="mt-[.3rem] text-[.78rem] leading-[1.5] text-[#8c6d6d]">{{ $alert->pesan }}</p>
                    <small class="text-[#a1887f]">
                        {{ $alert->is_resolved ? 'Sudah ditindaklanjuti' : 'Dalam pemantauan' }}
                    </small>
                </div>
            @empty
                <p class="p-6 text-center text-[#8c6d6d]">Tidak ada peringatan untuk siswa ini.</p>
            @endforelse
        </section>
    </div>
</x-layouts.app>
