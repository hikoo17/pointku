@php($title = 'Daftar Siswa')
@php($navigation = [['wali-kelas.dashboard', 'Ringkasan kelas','dashboard'], ['wali-kelas.students', 'Daftar siswa','users'], ['wali-kelas.notifications', 'Notifikasi','bell']])

<x-layouts.app :title="$title" :navigation="$navigation">
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
                <svg>
                    <use href="#icon-search"></use>
                </svg>
                Cari siswa
            </button>
            <a class="inline-flex justify-center items-center gap-[.55rem] min-h-[42px] rounded-[10px] border border-[#fce4c4] bg-white px-[1rem] py-[.72rem] text-[.8rem] font-[750] text-[#5d4037] shadow-[0_5px_18px_rgba(74,28,28,.03)] transition hover:bg-[#fff8e1]" href="{{ route('wali-kelas.students') }}">Reset</a>
        </form>
    </section>

    <section class="overflow-hidden rounded-[15px] border border-[#fce4c4] bg-white shadow-[0_5px_20px_rgba(74,28,28,.025)] mt-[1.2rem]">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-separate min-w-[760px]">
                <thead>
                    <tr>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Siswa</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Pelanggaran</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Apresiasi</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Saldo</th>
                        <th class="px-[1.5rem] py-[.75rem] text-left text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Status</th>
                        <th class="px-[1.5rem] py-[.75rem] text-center text-[.6rem] font-bold uppercase tracking-[.09em] text-[#8d6e63] bg-[#fff8e1]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <div class="flex items-center gap-[.7rem]">
                                    <span class="grid h-[31px] w-[31px] place-items-center rounded-[9px] bg-[#6d1a1a] font-[850] text-white">{{ substr($student->user->nama_lengkap,0,1) }}</span>
                                    <span>
                                        <strong class="block text-[.75rem] text-[#4a1c1c]">{{ $student->user->nama_lengkap }}</strong>
                                        <small class="mt-[.18rem] block text-[.61rem] text-[#a1887f]">{{ $student->nisn }}</small>
                                    </span>
                                </div>
                            </td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#c62828] border-t border-[#fff3e0]">{{ $student->total_poin_pelanggaran }}</td>
                            <td class="px-[1.5rem] py-[1rem] text-[.72rem] text-[#5d4037] border-t border-[#fff3e0]">+{{ $student->total_poin_apresiasi }}</td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]"><strong>{{ $student->saldo_poin }}</strong></td>
                            <td class="px-[1.5rem] py-[1rem] border-t border-[#fff3e0]">
                                <span class="inline-flex items-center gap-[.35rem] rounded-[99px] px-[.55rem] py-[.3rem] text-[.58rem] font-extrabold capitalize text-[#bf360c] bg-[#ffe0b2]">
                                    {{ $student->total_poin_pelanggaran >= 25 ? 'Perlu dipantau' : 'Normal' }}
                                </span>
                            </td>
                            <td class="px-[1.5rem] py-[1rem] text-center border-t border-[#fff3e0]">
                                <a class="inline-flex items-center gap-[.4rem] text-[.7rem] font-extrabold text-[#6d1a1a]" href="{{ route('wali-kelas.students.show',$student) }}">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="h-[180px] text-center text-[#a1887f] border-t border-[#fff3e0]">
                                <span class="mx-auto mb-[.7rem] grid h-[42px] w-[42px] place-items-center rounded-[12px] bg-[#ffebee] text-[#b71c1c]">
                                    <svg>
                                        <use href="#icon-users"></use>
                                    </svg>
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
