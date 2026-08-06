@php($title = 'Daftar Siswa')

<x-layouts.app :title="$title" >
    <x-dashboard
        title="Siswa kelas {{ $kelas->nama_kelas }}"
        eyebrow="MONITORING KELAS"
        copy="Cari siswa dan buka detail perkembangan poin yang telah divalidasi."
    />

    <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)]">
        <form method="GET" class="flex flex-wrap items-end gap-[.7rem] p-[1.5rem]">
            <label class="min-w-[150px] flex-1 text-[.72rem] font-bold text-[#5d4037]">
                Pencarian
                <input name="q" value="{{ request('q') }}" placeholder="Nama atau NISN" class="mt-[.4rem] min-w-0 flex-1 rounded-[11px] border border-[#fce4c4] bg-white p-[.9rem_1rem] text-[#4a1c1c] outline-none">
            </label>
            <label class="min-w-[150px] flex-1 text-[.72rem] font-bold text-[#5d4037]">
                Status
                <select name="status" class="mt-[.4rem] min-w-0 flex-1 rounded-[11px] border border-[#fce4c4] bg-white p-[.9rem_1rem] text-[#4a1c1c] outline-none">
                    <option value="">Semua status</option>
                    <option value="normal" @selected(request('status')==='normal')>Normal</option>
                    <option value="dipantau" @selected(request('status')==='dipantau')>Perlu dipantau</option>
                </select>
            </label>
            <button class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border-0 bg-[#6d1a1a] px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-white shadow-[0_8px_20px_#6d1a1a38] transition hover:-translate-y-px hover:bg-[#5a1515]" type="submit">
                <i data-lucide="users" class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border border-[#fce4c4] bg-white px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-[#5d4037] shadow-[0_5px_18px_rgba(74,28,28,.03)] transition hover:bg-[#fff8e1]"></i>
                                </span>
                                Siswa tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $students->links() }}
    </section>
</x-layouts.app>
