@php
    $title = 'Catatan Poin';
@endphp

<x-layouts.app :title="$title">
    <x-dashboard
        title="Catatan poin"
        eyebrow="KEJADIAN SISWA"
        copy="Catat pelanggaran atau apresiasi dengan kronologi yang dapat ditindaklanjuti."
    />

    {{-- Form Pencatatan --}}
    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="border-b border-slate-100 px-5 py-4">
            <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">FORM KEJADIAN</span>
            <h3 class="text-base font-bold text-slate-900">Catat poin baru</h3>
        </div>
        <form method="POST" action="{{ route('guru.records.store') }}" enctype="multipart/form-data" class="p-5" data-record-form>
            @csrf
            <div class="grid grid-cols-1 gap-4 min-[461px]:grid-cols-2">
                <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                    Siswa
                    <select name="siswa_id" required class="min-w-0 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50">
                        <option value="">Pilih siswa</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->user->nama_lengkap }} · {{ $student->nisn }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                    Kategori
                    <select name="kategori_poin_id" required class="min-w-0 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50">
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ ucfirst($category->jenis) }} · {{ $category->nama_kategori }} ({{ $category->bobot_poin }})
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                    Tanggal
                    <input type="date" name="tanggal" value="{{ now()->format('Y-m-d') }}" required class="min-w-0 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50">
                </label>
            </div>

            <label class="mt-4 grid gap-1.5 text-[0.68rem] font-bold uppercase tracking-wider text-slate-500">
                Kronologi
                <textarea name="keterangan" required placeholder="Jelaskan kejadian secara objektif" class="min-h-[120px] min-w-0 resize-y rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50"></textarea>
            </label>

            <section class="mt-5 overflow-hidden rounded-xl border border-slate-200 bg-slate-50/60" data-photo-picker>
                <div class="flex flex-col gap-3 border-b border-slate-200 bg-white px-4 py-4 min-[561px]:flex-row min-[561px]:items-center min-[561px]:justify-between">
                    <div>
                        <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">BUKTI FOTO</span>
                        <h4 class="text-sm font-bold text-slate-900">Dokumentasi kejadian</h4>
                        <p class="mt-0.5 text-xs font-medium text-slate-400">Maksimal 5 foto, masing-masing 2 MB.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" class="flex items-center justify-center gap-2 rounded-lg bg-[#5c1919] px-3 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#4a1414]" data-camera-open>
                            <i data-lucide="camera" class="h-4 w-4"></i>
                            Buka kamera
                        </button>
                        <label class="flex cursor-pointer items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50">
                            <i data-lucide="images" class="h-4 w-4"></i>
                            Pilih galeri
                            <input type="file" accept="image/*" multiple class="sr-only" data-photo-source>
                        </label>
                    </div>
                </div>
                <input type="file" name="bukti_foto[]" accept="image/*" multiple class="hidden" data-photo-target>
                <div class="p-4">
                    <div class="flex min-h-40 items-center justify-center rounded-lg border border-dashed border-slate-300 bg-white px-4 py-8 text-center" data-photo-empty>
                        <div>
                            <i data-lucide="image-plus" class="mx-auto h-8 w-8 text-slate-300"></i>
                            <p class="mt-2 text-xs font-semibold text-slate-500">Belum ada foto dipilih</p>
                            <p class="mt-1 text-[0.68rem] text-slate-400">Buka kamera untuk memotret langsung atau pilih beberapa foto dari galeri.</p>
                        </div>
                    </div>
                    <div class="hidden grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5" data-photo-preview></div>
                    <p class="mt-3 hidden text-xs font-medium text-slate-500" data-photo-summary></p>
                </div>
            </section>

            <dialog class="m-0 h-dvh max-h-none w-screen max-w-none overflow-hidden border-0 bg-black p-0 text-white backdrop:bg-black" data-camera-dialog>
                <div class="grid h-dvh min-h-0 grid-rows-[auto_minmax(0,1fr)_auto] bg-black">
                <div class="z-10 flex items-center justify-between border-b border-white/10 bg-slate-950 px-4 py-3 sm:px-6">
                    <div>
                        <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-amber-300">MODE JEPRET</p>
                        <h4 class="text-sm font-bold">Bukti foto <span class="font-medium text-slate-400" data-camera-count>0/5</span></h4>
                    </div>
                    <button type="button" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20" aria-label="Tutup kamera" data-camera-close>
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <div class="relative min-h-0 overflow-hidden bg-black">
                    <video autoplay playsinline muted class="h-full w-full object-cover sm:object-contain" data-camera-video></video>
                    <canvas class="hidden" data-camera-canvas></canvas>
                    <div class="pointer-events-none absolute inset-5 rounded-xl border border-white/15 sm:inset-8"></div>
                    <div class="absolute inset-0 flex items-center justify-center bg-slate-950/70 p-4" data-camera-status>
                        <p class="max-w-sm rounded-xl border border-white/10 bg-slate-900/95 px-4 py-3 text-center text-xs font-semibold shadow-xl" data-camera-status-text>Membuka kamera...</p>
                    </div>
                </div>
                <div class="z-10 border-t border-white/10 bg-slate-950 px-5 pb-[max(1.25rem,env(safe-area-inset-bottom))] pt-4">
                    <div class="mx-auto grid w-full max-w-md grid-cols-3 items-center">
                        <span class="text-[0.65rem] font-medium leading-tight text-slate-400">Pastikan objek terlihat jelas</span>
                        <button type="button" class="mx-auto grid h-18 w-18 place-items-center rounded-full border-4 border-white bg-transparent p-1 transition active:scale-95 disabled:cursor-not-allowed disabled:border-slate-600 disabled:opacity-50" aria-label="Jepret foto" disabled data-camera-capture>
                            <span class="h-full w-full rounded-full bg-white"></span>
                        </button>
                        <button type="button" class="ml-auto grid h-11 w-11 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20 disabled:cursor-not-allowed disabled:opacity-30" title="Ganti kamera" aria-label="Ganti kamera" data-camera-switch>
                            <i data-lucide="switch-camera" class="h-5 w-5"></i>
                        </button>
                    </div>
                </div>
                </div>
            </dialog>

            <div class="mt-5 flex justify-end">
                <button class="inline-flex min-w-36 items-center justify-center gap-1.5 rounded-lg bg-[#5c1919] px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#4a1414] disabled:cursor-wait disabled:bg-[#7d4a4a]" type="submit" data-record-submit>
                    <i data-lucide="plus" class="h-4 w-4" data-submit-icon></i>
                    <span data-submit-label>Simpan catatan</span>
                </button>
            </div>
        </form>
    </section>

    {{-- Daftar Catatan --}}
    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-xs">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 min-[641px]:flex-row min-[641px]:items-center min-[641px]:justify-between">
            <div>
                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#5c1919]">DAFTAR KEJADIAN</span>
                <h3 class="text-base font-bold text-slate-900">Catatan poin siswa</h3>
            </div>
            <form method="GET" action="{{ route('guru.records') }}" class="flex items-center gap-2">
                <div class="relative">
                    <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama siswa..." class="w-full rounded-lg border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-400 focus:bg-slate-50 min-[641px]:w-64">
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @if($records->isNotEmpty())
                    @foreach($records as $record)
                        <tr class="transition hover:bg-slate-50/80">
                            {{-- Siswa --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#5c1919] font-bold text-white shadow-2xs">
                                        {{ strtoupper(substr($record->siswa->user->nama_lengkap ?? 'S', 0, 1)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <strong class="block truncate text-xs font-bold text-slate-800">{{ $record->siswa->user->nama_lengkap }}</strong>
                                        <small class="block text-[0.68rem] font-medium text-slate-400">{{ $record->siswa->nisn ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-5 py-3.5 font-medium text-slate-600">
                                {{ $record->kategoriPoin->nama_kategori }}
                                <small class="block text-[0.68rem] capitalize text-slate-400">{{ $record->kategoriPoin->jenis }}</small>
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-5 py-3.5 font-medium text-slate-600">
                                {{ $record->tanggal->format('d/m/Y') }}
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-5 py-3.5">
                                @php
                                    $statusStyles = [
                                        'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                        'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                        'menunggu_validasi' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                        'draft' => 'bg-slate-100 text-slate-600 border-slate-200/60',
                                    ];
                                    $statusLabel = [
                                        'disetujui' => 'Disetujui',
                                        'ditolak' => 'Ditolak',
                                        'menunggu_validasi' => 'Menunggu',
                                        'draft' => 'Draft',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-md border px-2.5 py-1 text-[0.68rem] font-semibold capitalize {{ $statusStyles[$record->status_validasi] ?? 'bg-slate-100 text-slate-600 border-slate-200/60' }}">
                                    {{ $statusLabel[$record->status_validasi] ?? $record->status_validasi }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-2">
                                    <a class="group inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200/70 hover:text-slate-900" href="{{ route('guru.records.show', $record) }}">
                                        <i data-lucide="eye" class="h-3.5 w-3.5 fill-none stroke-current"></i>
                                        Detail
                                    </a>
                                    @if(auth()->user()->hasRole('Guru BK') && $record->status_validasi === 'menunggu_validasi')
                                        <form method="POST" action="{{ route('guru.records.validate', $record) }}" class="inline-flex items-center gap-1.5">
                                            @csrf
                                            <button class="inline-flex items-center gap-1.5 rounded-lg bg-[#5c1919] px-3 py-1.5 text-xs font-semibold text-white shadow-xs transition hover:bg-[#4a1414]" name="status_validasi" value="disetujui" data-confirm="Catatan poin ini akan disetujui." data-confirm-title="Setujui catatan?" data-confirm-button="Ya, setujui">
                                                <i data-lucide="check" class="h-3.5 w-3.5 fill-none stroke-current"></i>
                                                Setujui
                                            </button>
                                            <button class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50" name="status_validasi" value="ditolak" data-confirm="Catatan poin ini akan ditolak." data-confirm-title="Tolak catatan?" data-confirm-button="Ya, tolak">
                                                <i data-lucide="x" class="h-3.5 w-3.5 fill-none stroke-current"></i>
                                                Tolak
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                    <i data-lucide="notebook-pen" class="h-5 w-5 fill-none stroke-current"></i>
                                </div>
                                <span class="text-xs font-medium">Belum ada catatan.</span>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
            <div class="border-t border-slate-100 px-5 py-3 bg-slate-50/50">
                {{ $records->links() }}
            </div>
        @endif
    </section>

    <script>
        document.querySelectorAll('[data-photo-picker]').forEach((picker) => {
            const sources = [...picker.querySelectorAll('[data-photo-source]')];
            const target = picker.querySelector('[data-photo-target]');
            const preview = picker.querySelector('[data-photo-preview]');
            const empty = picker.querySelector('[data-photo-empty]');
            const summary = picker.querySelector('[data-photo-summary]');
            const form = picker.closest('form');
            const cameraOpen = picker.querySelector('[data-camera-open]');
            const cameraDialog = form.querySelector('[data-camera-dialog]');
            const cameraClose = cameraDialog.querySelector('[data-camera-close]');
            const cameraVideo = cameraDialog.querySelector('[data-camera-video]');
            const cameraCanvas = cameraDialog.querySelector('[data-camera-canvas]');
            const cameraStatus = cameraDialog.querySelector('[data-camera-status]');
            const cameraStatusText = cameraDialog.querySelector('[data-camera-status-text]');
            const cameraSwitch = cameraDialog.querySelector('[data-camera-switch]');
            const cameraCapture = cameraDialog.querySelector('[data-camera-capture]');
            const cameraCount = cameraDialog.querySelector('[data-camera-count]');
            const submitButton = form.querySelector('[data-record-submit]');
            const submitIcon = submitButton.querySelector('[data-submit-icon]');
            const submitLabel = submitButton.querySelector('[data-submit-label]');
            let files = [];
            let cameraStream = null;
            let cameraDevices = [];
            let cameraIndex = 0;
            let submitting = false;

            const syncTarget = () => {
                const transfer = new DataTransfer();
                files.forEach((file) => transfer.items.add(file));
                target.files = transfer.files;
            };

            const render = () => {
                preview.replaceChildren();

                files.forEach((file, index) => {
                    const item = document.createElement('div');
                    const image = document.createElement('img');
                    const name = document.createElement('span');
                    const remove = document.createElement('button');
                    const url = URL.createObjectURL(file);
                    item.className = 'group relative aspect-square overflow-hidden rounded-lg border border-slate-200 bg-slate-100';
                    image.className = 'h-full w-full object-cover';
                    image.src = url;
                    image.alt = `Preview foto ${index + 1}`;
                    name.className = 'absolute inset-x-0 bottom-0 truncate bg-slate-950/65 px-2 py-1.5 text-[0.65rem] font-medium text-white';
                    name.textContent = file.name;
                    remove.type = 'button';
                    remove.className = 'absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-white/95 text-slate-700 shadow transition hover:bg-rose-50 hover:text-rose-600';
                    remove.setAttribute('aria-label', `Hapus foto ${index + 1}`);
                    remove.textContent = '×';
                    image.addEventListener('load', () => URL.revokeObjectURL(url), { once: true });
                    remove.addEventListener('click', () => {
                        files.splice(index, 1);
                        syncTarget();
                        render();
                    });
                    item.append(image, name, remove);
                    preview.appendChild(item);
                });

                empty.classList.toggle('hidden', files.length > 0);
                preview.classList.toggle('hidden', files.length === 0);
                preview.classList.toggle('grid', files.length > 0);
                summary.textContent = files.length ? `${files.length} dari 5 foto siap diunggah` : '';
                summary.classList.toggle('hidden', files.length === 0);
                summary.classList.remove('text-rose-600');
                summary.classList.add('text-slate-500');
                cameraCount.textContent = `${files.length}/5`;
            };

            const stopCamera = () => {
                cameraStream?.getTracks().forEach((track) => track.stop());
                cameraStream = null;
                cameraVideo.srcObject = null;
                cameraCapture.disabled = true;
            };

            const cameraErrorMessage = (error) => {
                if (!window.isSecureContext) return 'Kamera hanya dapat dibuka melalui HTTPS atau localhost.';
                if (!navigator.mediaDevices?.getUserMedia) return 'Browser ini tidak mendukung akses kamera langsung.';
                if (error?.name === 'NotAllowedError' || error?.name === 'SecurityError') return 'Izin kamera ditolak. Izinkan akses kamera pada pengaturan browser.';
                if (error?.name === 'NotFoundError' || error?.name === 'DevicesNotFoundError') return 'Kamera tidak ditemukan pada perangkat ini.';
                if (error?.name === 'NotReadableError' || error?.name === 'TrackStartError') return 'Kamera sedang digunakan aplikasi lain.';
                return 'Kamera gagal dibuka. Tutup aplikasi kamera lain lalu coba kembali.';
            };

            const showCameraStatus = (message = null) => {
                cameraStatus.classList.toggle('hidden', !message);
                cameraStatusText.textContent = message ?? '';
            };

            const startCamera = async (deviceId = null) => {
                stopCamera();
                showCameraStatus('Membuka kamera...');
                cameraSwitch.disabled = true;

                if (!window.isSecureContext || !navigator.mediaDevices?.getUserMedia) {
                    throw new DOMException('Camera API unavailable', 'SecurityError');
                }

                const video = deviceId
                    ? { deviceId: { exact: deviceId }, width: { ideal: 1600 }, height: { ideal: 1200 } }
                    : { facingMode: { ideal: 'environment' }, width: { ideal: 1600 }, height: { ideal: 1200 } };
                cameraStream = await navigator.mediaDevices.getUserMedia({ video, audio: false });
                cameraVideo.srcObject = cameraStream;
                await cameraVideo.play();

                cameraDevices = (await navigator.mediaDevices.enumerateDevices()).filter((device) => device.kind === 'videoinput');
                const activeDeviceId = cameraStream.getVideoTracks()[0]?.getSettings().deviceId;
                const activeIndex = cameraDevices.findIndex((device) => device.deviceId === activeDeviceId);
                if (activeIndex >= 0) cameraIndex = activeIndex;

                cameraSwitch.disabled = cameraDevices.length < 2;
                cameraCapture.disabled = false;
                showCameraStatus();
            };

            cameraOpen.addEventListener('click', async () => {
                if (files.length >= 5) {
                    summary.textContent = 'Maksimal 5 foto dapat dipilih.';
                    summary.classList.remove('hidden', 'text-slate-500');
                    summary.classList.add('text-rose-600');
                    return;
                }

                cameraDialog.showModal();
                try {
                    await startCamera();
                } catch (error) {
                    stopCamera();
                    showCameraStatus(cameraErrorMessage(error));
                }
            });

            cameraClose.addEventListener('click', () => cameraDialog.close());
            cameraDialog.addEventListener('click', (event) => {
                if (event.target === cameraDialog) cameraDialog.close();
            });
            cameraDialog.addEventListener('close', stopCamera);

            cameraSwitch.addEventListener('click', async () => {
                if (cameraDevices.length < 2) return;
                cameraIndex = (cameraIndex + 1) % cameraDevices.length;
                try {
                    await startCamera(cameraDevices[cameraIndex].deviceId);
                } catch (error) {
                    stopCamera();
                    showCameraStatus(cameraErrorMessage(error));
                }
            });

            cameraCapture.addEventListener('click', () => {
                if (!cameraVideo.videoWidth || files.length >= 5) return;

                const scale = Math.min(1, 1600 / cameraVideo.videoWidth);
                cameraCanvas.width = Math.round(cameraVideo.videoWidth * scale);
                cameraCanvas.height = Math.round(cameraVideo.videoHeight * scale);
                cameraCanvas.getContext('2d').drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);
                cameraCapture.disabled = true;

                cameraCanvas.toBlob((blob) => {
                    if (!blob) {
                        cameraCapture.disabled = false;
                        showCameraStatus('Foto gagal diproses. Silakan jepret ulang.');
                        return;
                    }

                    const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
                    files.push(new File([blob], `bukti-${timestamp}.jpg`, { type: 'image/jpeg' }));
                    syncTarget();
                    render();
                    cameraDialog.close();
                }, 'image/jpeg', 0.82);
            });

            sources.forEach((source) => source.addEventListener('change', () => {
                const selected = [...source.files];

                if (files.length + selected.length > 5) {
                    summary.textContent = 'Maksimal 5 foto dapat dipilih.';
                    summary.classList.remove('hidden', 'text-slate-500');
                    summary.classList.add('text-rose-600');
                    source.value = '';
                    return;
                }

                files.push(...selected);
                source.value = '';
                syncTarget();
                render();
            }));

            form.addEventListener('submit', (event) => {
                if (submitting) {
                    event.preventDefault();
                    return;
                }

                submitting = true;
                stopCamera();
                submitButton.disabled = true;
                submitButton.setAttribute('aria-busy', 'true');
                submitIcon.setAttribute('data-lucide', 'loader-circle');
                submitIcon.classList.add('animate-spin');
                submitLabel.textContent = 'Menyimpan...';
                window.lucide?.createIcons();
            });
            window.addEventListener('pagehide', stopCamera);
        });
    </script>
</x-layouts.app>
