<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ApprovalLaporan;
use App\Models\AturanThreshold;
use App\Models\CatatanPoin;
use App\Models\KategoriPoin;
use App\Models\Kelas;
use App\Models\LaporanKesiswaan;
use App\Models\Notifikasi;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\SuratPanggilan;
use App\Models\User;
use App\Services\SuratPanggilanWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function redirect(Request $request)
    {
        return redirect()->route(match ($request->user()->role->nama_role) {
            'Kesiswaan' => 'kesiswaan.dashboard',
            'Guru BK' => 'guru.dashboard',
            'Guru Pelapor' => 'guru.records',
            'Wali Kelas' => 'wali-kelas.dashboard',
            'Siswa' => 'siswa.dashboard',
            default => 'login',
        });
    }

    public function kesiswaan()
    {
        $recordCounts = DB::table('catatan_poin')
            ->join('kategori_poin', 'kategori_poin.id', '=', 'catatan_poin.kategori_poin_id')
            ->join('siswa', 'siswa.id', '=', 'catatan_poin.siswa_id')
            ->where('catatan_poin.status_validasi', 'disetujui')
            ->select(
                'siswa.kelas_id',
                DB::raw("SUM(CASE WHEN kategori_poin.jenis = 'pelanggaran' THEN 1 ELSE 0 END) as violations"),
                DB::raw("SUM(CASE WHEN kategori_poin.jenis = 'apresiasi' THEN 1 ELSE 0 END) as appreciations"),
            )
            ->groupBy('siswa.kelas_id');

        $classes = DB::table('kelas')
            ->leftJoin('siswa', 'kelas.id', '=', 'siswa.kelas_id')
            ->leftJoinSub($recordCounts, 'record_counts', fn ($join) => $join->on('kelas.id', '=', 'record_counts.kelas_id'))
            ->select(
                'kelas.id',
                'kelas.nama_kelas',
                DB::raw('COUNT(siswa.id) as total_siswa'),
                DB::raw('COALESCE(MAX(record_counts.violations), 0) as pelanggaran'),
                DB::raw('COALESCE(MAX(record_counts.appreciations), 0) as apresiasi'),
            )
            ->groupBy('kelas.id', 'kelas.nama_kelas')
            ->get();

        return view('dashboards.kesiswaan', [
            'stats' => $this->schoolStats(),
            'reports' => LaporanKesiswaan::with(['siswa.user', 'bk'])->latest('diajukan_pada')->limit(6)->get(),
            'students' => Siswa::with(['user', 'kelas'])->where('total_poin_pelanggaran', '>=', 25)->orderByDesc('total_poin_pelanggaran')->limit(6)->get(),
            'classes' => $classes,
        ]);
    }

    public function teacher(Request $request)
    {
        $isReporter = $request->user()->hasRole('Guru Pelapor');
        $records = CatatanPoin::with(['siswa.user', 'kategoriPoin'])
            ->when($isReporter, fn ($query) => $query->where('pencatat_id', $request->user()->id))
            ->latest()->limit(7)->get();

        return view('dashboards.teacher', compact('records', 'isReporter') + [
            'pendingCount' => CatatanPoin::where('status_validasi', 'menunggu_validasi')->count(),
            'studentCount' => Siswa::count(),
            'reportCount' => LaporanKesiswaan::where('bk_id', $request->user()->id)->where('status', 'pending')->count(),
            'letterCount' => SuratPanggilan::count(),
        ]);
    }

    public function homeroom(Request $request)
    {
        $kelas = $this->homeroomFor($request);
        $students = $kelas->siswa()->with('user')->orderByDesc('total_poin_pelanggaran')->get();
        $alerts = Notifikasi::with('siswa.user')->whereHas('siswa', fn ($q) => $q->where('kelas_id', $kelas->id))->latest()->limit(5)->get();
        $recentRecords = CatatanPoin::with(['siswa.user', 'kategoriPoin'])->whereHas('siswa', fn ($q) => $q->where('kelas_id', $kelas->id))->where('status_validasi', 'disetujui')->latest('tanggal')->limit(6)->get();

        return view('dashboards.homeroom', compact('kelas', 'students', 'alerts', 'recentRecords'));
    }

    public function homeroomStudents(Request $request)
    {
        $kelas = $this->homeroomFor($request);
        $query = $kelas->siswa()->with('user');
        if ($request->filled('q')) {
            $query->where(fn ($q) => $q->where('nisn', 'like', '%'.$request->q.'%')->orWhereHas('user', fn ($user) => $user->where('nama_lengkap', 'like', '%'.$request->q.'%')));
        }
        if ($request->status === 'dipantau') {
            $query->where('total_poin_pelanggaran', '>=', 25);
        }
        if ($request->status === 'normal') {
            $query->where('total_poin_pelanggaran', '<', 25);
        }

        return view('wali-kelas.students', ['kelas' => $kelas, 'students' => $query->orderByDesc('total_poin_pelanggaran')->paginate(20)->withQueryString()]);
    }

    public function homeroomStudent(Request $request, Siswa $siswa)
    {
        $kelas = $this->homeroomFor($request);
        abort_unless($siswa->kelas_id === $kelas->id, 404);
        $siswa->load(['user', 'kelas']);
        $records = $siswa->catatanPoin()->with(['kategoriPoin', 'pencatat'])->where('status_validasi', 'disetujui')->latest('tanggal')->paginate(12);
        $alerts = $siswa->notifikasi()->with('aturanThreshold')->latest()->get();

        return view('wali-kelas.student', compact('kelas', 'siswa', 'records', 'alerts'));
    }

    public function homeroomNotifications(Request $request)
    {
        $kelas = $this->homeroomFor($request);
        $notifications = Notifikasi::with(['siswa.user', 'aturanThreshold'])->whereHas('siswa', fn ($q) => $q->where('kelas_id', $kelas->id))->latest()->paginate(15);

        return view('wali-kelas.notifications', compact('kelas', 'notifications'));
    }

    public function readHomeroomNotification(Request $request, Notifikasi $notifikasi)
    {
        $kelas = $this->homeroomFor($request);
        abort_unless($notifikasi->siswa()->where('kelas_id', $kelas->id)->exists(), 404);
        if (! $notifikasi->dibaca_pada) {
            $notifikasi->update(['dibaca_pada' => now()]);
        }

        return back()->with('success', 'Notifikasi kelas ditandai sudah dibaca.');
    }

    public function student(Request $request)
    {
        $student = $request->user()->siswa?->load('kelas');
        abort_unless($student, 404, 'Profil siswa belum terhubung.');
        $records = $student->catatanPoin()->with('kategoriPoin')->where('status_validasi', 'disetujui')->latest('tanggal')->limit(6)->get();
        $alerts = $student->notifikasi()->latest()->limit(4)->get();
        $status = match (true) {
            $student->total_poin_pelanggaran >= 100 => ['Penanganan khusus', 'berat', 100],
            $student->total_poin_pelanggaran >= 50 => ['Panggilan orang tua', 'sedang', 100],
            $student->total_poin_pelanggaran >= 25 => ['Dalam pemantauan', 'ringan', 50],
            default => ['Baik', 'normal', 25],
        };

        return view('dashboards.student', compact('student', 'records', 'alerts', 'status'));
    }

    public function studentHistory(Request $request)
    {
        $student = $this->studentFor($request);
        $query = $student->catatanPoin()->with(['kategoriPoin', 'pencatat'])
            ->where('status_validasi', 'disetujui');
        if ($request->filled('jenis')) {
            $query->whereHas('kategoriPoin', fn ($q) => $q->where('jenis', $request->jenis));
        }
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        return view('siswa.history', ['student' => $student, 'records' => $query->latest('tanggal')->paginate(15)->withQueryString()]);
    }

    public function studentRecord(Request $request, CatatanPoin $catatan)
    {
        $student = $this->studentFor($request);
        abort_unless($catatan->siswa_id === $student->id && $catatan->status_validasi === 'disetujui', 404);

        return view('siswa.record', ['student' => $student, 'record' => $catatan->load(['kategoriPoin', 'pencatat'])]);
    }

    public function studentNotifications(Request $request)
    {
        $student = $this->studentFor($request);

        return view('siswa.notifications', ['student' => $student, 'notifications' => $student->notifikasi()->with('aturanThreshold')->latest()->paginate(15)]);
    }

    public function readStudentNotification(Request $request, Notifikasi $notifikasi)
    {
        $student = $this->studentFor($request);
        abort_unless($notifikasi->siswa_id === $student->id, 404);
        if (! $notifikasi->dibaca_pada) {
            $notifikasi->update(['dibaca_pada' => now()]);
        }

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function records(Request $request)
    {
        $query = CatatanPoin::with(['siswa.user', 'siswa.kelas', 'kategoriPoin', 'pencatat']);
        if ($request->user()->hasRole('Guru Pelapor')) {
            $query->where('pencatat_id', $request->user()->id);
        }
        if ($request->filled('q')) {
            $query->whereHas('siswa.user', fn ($q) => $q->where('nama_lengkap', 'like', '%'.$request->q.'%'));
        }

        return view('guru.records', [
            'records' => $query->latest()->paginate(15)->withQueryString(),
            'students' => Siswa::with(['user', 'kelas'])->orderBy('nisn')->get(),
            'categories' => KategoriPoin::orderBy('jenis')->orderBy('bobot_poin')->get(),
        ]);
    }

    public function teacherRecord(Request $request, CatatanPoin $catatan)
    {
        if ($request->user()->hasRole('Guru Pelapor')) {
            abort_unless($catatan->pencatat_id === $request->user()->id, 404);
        }

        $catatan->load(['kategoriPoin', 'pencatat', 'siswa.user']);

        return view('guru.record', ['record' => $catatan]);
    }

    public function validateRecord(Request $request, CatatanPoin $catatan)
    {
        abort_if($request->user()->hasRole('Guru Pelapor'), 403);
        $data = $request->validate([
            'status_validasi' => ['required', 'in:disetujui,ditolak'],
        ]);
        abort_unless($catatan->status_validasi === 'menunggu_validasi', 422, 'Catatan ini sudah diproses.');
        $catatan->update($data);

        return back()->with('success', 'Validasi catatan berhasil diperbarui.');
    }

    public function studentRecap(Request $request)
    {
        abort_if($request->user()->hasRole('Guru Pelapor'), 403);

        return view('guru.students', [
            'students' => Siswa::with(['user', 'kelas'])->orderByDesc('total_poin_pelanggaran')->paginate(20),
        ]);
    }

    public function storeRecord(Request $request)
    {
        $data = $request->validate([
            'siswa_id' => ['required', 'exists:siswa,id'], 'kategori_poin_id' => ['required', 'exists:kategori_poin,id'],
            'tanggal' => ['required', 'date'], 'keterangan' => ['required', 'string', 'max:3000'],
            'bukti_foto' => ['nullable', 'array', 'max:5'],
            'bukti_foto.*' => ['image', 'max:2048'], 'status_validasi' => ['nullable', 'in:draft,disetujui'],
        ]);
        $data['pencatat_id'] = $request->user()->id;
        $data['status_validasi'] = $request->user()->hasRole('Guru Pelapor') ? 'menunggu_validasi' : ($data['status_validasi'] ?? 'disetujui');
        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = json_encode(array_map(
                fn ($foto) => $foto->store('bukti-poin', 'public'),
                $request->file('bukti_foto')
            ));
        }
        CatatanPoin::create($data);

        return back()->with('success', 'Catatan kejadian berhasil disimpan.');
    }

    public function teacherReports(Request $request)
    {
        abort_if($request->user()->hasRole('Guru Pelapor'), 403);

        return view('guru.reports', [
            'reports' => LaporanKesiswaan::with(['siswa.user', 'kesiswaan'])->where('bk_id', $request->user()->id)->latest()->paginate(15),
            'students' => Siswa::with(['user', 'kelas'])->where('total_poin_pelanggaran', '>=', 25)->orderByDesc('total_poin_pelanggaran')->get(),
        ]);
    }

    public function storeReport(Request $request)
    {
        abort_if($request->user()->hasRole('Guru Pelapor'), 403);
        $data = $request->validate(['siswa_id' => ['required', 'exists:siswa,id'], 'jenis_tindakan' => ['required', 'string', 'max:255']]);
        LaporanKesiswaan::create($data + ['bk_id' => $request->user()->id, 'status' => 'pending', 'diajukan_pada' => now()]);

        return back()->with('success', 'Laporan berhasil dikirim ke Kesiswaan.');
    }

    public function reports(Request $request)
    {
        $reports = LaporanKesiswaan::with(['siswa.user', 'siswa.kelas', 'bk'])->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))->latest()->paginate(15)->withQueryString();

        return view('kesiswaan.reports', compact('reports'));
    }

    public function approval(Request $request, LaporanKesiswaan $laporan)
    {
        $data = $request->validate(['status' => ['required', 'in:pending,disetujui,ditolak'], 'catatan_approval' => ['nullable', 'string', 'max:2000']]);
        DB::transaction(function () use ($request, $laporan, $data) {
            $laporan->update(['status' => $data['status'], 'kesiswaan_id' => $request->user()->id, 'catatan_kesiswaan' => $data['catatan_approval'] ?? null, 'selesai_pada' => $data['status'] === 'pending' ? null : now()]);
            ApprovalLaporan::create(['laporan_kesiswaan_id' => $laporan->id, 'approver_id' => $request->user()->id, 'status' => $data['status'], 'catatan_approval' => $data['catatan_approval'] ?? null, 'disetujui_pada' => $data['status'] === 'disetujui' ? now() : null]);
        });

        return back()->with('success', 'Keputusan laporan berhasil disimpan.');
    }

    public function classDetail(Kelas $kelas)
    {
        $students = $kelas->siswa()->with('user')->orderByDesc('total_poin_pelanggaran')->get();

        $violations = $kelas->catatanPoin()->where('status_validasi', 'disetujui')
            ->whereHas('kategoriPoin', fn ($query) => $query->where('jenis', 'pelanggaran'))
            ->count();
        $appreciations = $kelas->catatanPoin()->where('status_validasi', 'disetujui')
            ->whereHas('kategoriPoin', fn ($query) => $query->where('jenis', 'apresiasi'))
            ->count();

        return view('kesiswaan.class', compact('kelas', 'students') + [
            'summary' => [
                'students' => $students->count(),
                'violations' => $violations,
                'appreciations' => $appreciations,
                'attention' => $students->where('total_poin_pelanggaran', '>=', 25)->count(),
            ],
        ]);
    }

    public function letters()
    {
        return view('kesiswaan.letters', ['letters' => SuratPanggilan::with(['siswa.user', 'aturanThreshold'])->latest()->paginate(15)]);
    }

    public function teacherLetters(Request $request)
    {
        abort_if($request->user()->hasRole('Guru Pelapor'), 403);

        return view('guru.letters', [
            'letters' => SuratPanggilan::with(['siswa.user', 'aturanThreshold', 'laporanKesiswaan'])
                ->latest()->paginate(15),
        ]);
    }

    public function letter(Request $request, SuratPanggilan $surat)
    {
        abort_unless($request->user()->hasAnyRole(['Guru BK', 'Kesiswaan']), 403);
        $surat->load(['siswa.user', 'siswa.kelas', 'aturanThreshold', 'laporanKesiswaan', 'histories.user']);

        return view('letters.show', compact('surat'));
    }

    public function updateLetter(Request $request, SuratPanggilan $surat)
    {
        abort_unless($request->user()->hasRole('Guru BK'), 403);
        abort_unless(in_array($surat->status, ['draft', 'perlu_revisi'], true), 422, 'Surat tidak dapat diedit pada status ini.');
        $data = $request->validate([
            'tanggal_surat' => ['required', 'date'], 'alasan_pemanggilan' => ['required', 'string', 'max:3000'],
            'daftar_kejadian' => ['nullable', 'string', 'max:10000'], 'tindakan_direkomendasikan' => ['required', 'string', 'max:2000'],
            'catatan' => ['nullable', 'string', 'max:3000'],
        ]);
        $surat->update($data);

        return back()->with('success', 'Draf surat berhasil diperbarui.');
    }

    public function transitionLetter(Request $request, SuratPanggilan $surat, SuratPanggilanWorkflowService $workflow)
    {
        $data = $request->validate(['status' => ['required', 'string'], 'catatan' => ['nullable', 'string', 'max:3000']]);
        $workflow->transition($surat, $data['status'], $request->user(), $data['catatan'] ?? null);

        return back()->with('success', 'Status surat berhasil diperbarui.');
    }

    public function printLetter(Request $request, SuratPanggilan $surat)
    {
        abort_unless($request->user()->hasAnyRole(['Guru BK', 'Kesiswaan']), 403);
        abort_unless(in_array($surat->status, ['disetujui', 'dicetak', 'dikirim', 'selesai'], true), 422, 'Surat belum disetujui.');

        return view('pdf.surat-panggilan', ['surat' => $surat->load(['siswa.user', 'siswa.kelas'])]);
    }

    public function masterUsers(Request $request)
    {
        $users = User::with('role')->whereDoesntHave('role', fn ($query) => $query->where('nama_role', 'Siswa'))
            ->when($request->filled('q'), fn ($query) => $query->where(fn ($search) => $search
                ->where('nama_lengkap', 'like', '%'.$request->q.'%')
                ->orWhere('username', 'like', '%'.$request->q.'%')))
            ->orderBy('nama_lengkap')->paginate(20)->withQueryString();

        return view('kesiswaan.master.users', [
            'users' => $users,
            'roles' => Role::where('nama_role', '!=', 'Siswa')->orderBy('nama_role')->get(),
        ]);
    }

    public function storeMasterUser(Request $request)
    {
        $data = $this->validateMasterUser($request);
        User::create($data);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function updateMasterUser(Request $request, User $user)
    {
        abort_if($user->hasRole('Siswa'), 404);
        $data = $this->validateMasterUser($request, $user);
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }
        $user->update($data);

        return back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroyMasterUser(Request $request, User $user)
    {
        abort_if($user->hasRole('Siswa'), 404);
        abort_if($request->user()->is($user), 422, 'Akun yang sedang digunakan tidak dapat dihapus.');
        abort_if($user->kelas()->exists() || $user->catatanPoin()->exists(), 422, 'Pengguna masih terhubung dengan kelas atau catatan poin.');
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    public function masterClasses(Request $request)
    {
        return view('kesiswaan.master.classes', [
            'classes' => Kelas::with('waliKelas')
                ->withCount('siswa')
                ->withCount([
                    'catatanPoin as pelanggaran_count' => fn ($query) => $query->where('status_validasi', 'disetujui')->whereHas('kategoriPoin', fn ($kategori) => $kategori->where('jenis', 'pelanggaran')),
                    'catatanPoin as apresiasi_count' => fn ($query) => $query->where('status_validasi', 'disetujui')->whereHas('kategoriPoin', fn ($kategori) => $kategori->where('jenis', 'apresiasi')),
                    'siswa as perhatian_count' => fn ($query) => $query->where('total_poin_pelanggaran', '>=', 25),
                ])
                ->when($request->filled('q'), fn ($query) => $query->where(fn ($search) => $search
                    ->where('nama_kelas', 'like', '%'.$request->q.'%')
                    ->orWhereHas('waliKelas', fn ($teacher) => $teacher->where('nama_lengkap', 'like', '%'.$request->q.'%'))))
                ->orderBy('nama_kelas')->paginate(20)->withQueryString(),
            'homeroomTeachers' => User::whereHas('role', fn ($query) => $query->where('nama_role', 'Wali Kelas'))->orderBy('nama_lengkap')->get(),
        ]);
    }

    public function storeMasterClass(Request $request)
    {
        Kelas::create($this->validateMasterClass($request));

        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function updateMasterClass(Request $request, Kelas $kelas)
    {
        $kelas->update($this->validateMasterClass($request, $kelas));

        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroyMasterClass(Kelas $kelas)
    {
        abort_if($kelas->siswa()->exists(), 422, 'Kelas yang masih memiliki siswa tidak dapat dihapus.');
        $kelas->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    public function masterStudents(Request $request)
    {
        $status = in_array($request->status, ['aktif', 'nonaktif'], true) ? $request->status : 'aktif';
        $students = Siswa::with(['user', 'kelas'])
            ->withExists([
                'catatanPoin as has_catatan_poin',
                'laporanKesiswaan as has_laporan_kesiswaan',
                'notifikasi as has_notifikasi',
                'suratPanggilan as has_surat_panggilan',
            ])
            ->where('status', $status)
            ->when($request->filled('q'), fn ($query) => $query->where(fn ($search) => $search
            ->where('nisn', 'like', '%'.$request->q.'%')
            ->orWhereHas('user', fn ($user) => $user->where('nama_lengkap', 'like', '%'.$request->q.'%')->orWhere('username', 'like', '%'.$request->q.'%'))))
            ->orderBy('nisn')->paginate(20)->withQueryString();

        return view('kesiswaan.master.students', ['students' => $students, 'classes' => Kelas::orderBy('nama_kelas')->get(), 'status' => $status]);
    }

    public function importMasterStudents(Request $request)
    {
        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt', 'max:5120']]);
        $handle = fopen($request->file('file')->getRealPath(), 'rb');
        $headers = fgetcsv($handle, 0, ',');
        $headers = $headers === false ? [] : array_map(fn ($header) => strtolower(trim((string) $header)), $headers);
        $required = ['nama_lengkap', 'username', 'password', 'nisn', 'nama_kelas', 'jenis_kelamin'];
        if (array_diff($required, $headers)) {
            return back()->withErrors(['file' => 'Kolom wajib: '.implode(', ', $required).'.']);
        }
        $rows = [];
        $errors = [];
        $line = 1;
        while (($values = fgetcsv($handle, 0, ',')) !== false) {
            $line++;
            if (count($values) === 1 && trim((string) $values[0]) === '') {
                continue;
            }
            $row = array_combine($headers, array_pad($values, count($headers), null));
            $row['jenis_kelamin'] = strtoupper(trim((string) ($row['jenis_kelamin'] ?? '')));
            $validator = validator($row, ['nama_lengkap' => ['required', 'string', 'max:255'], 'username' => ['required', 'string', 'max:255'], 'password' => ['required', 'string', 'min:8'], 'nisn' => ['required', 'string', 'max:20'], 'nama_kelas' => ['required', 'string', 'max:100'], 'jenis_kelamin' => ['required', 'in:L,P']]);
            if ($validator->fails()) {
                $errors[] = 'Baris '.$line.': '.implode(' ', $validator->errors()->all());
            } else {
                $rows[] = $row;
            }
        }
        fclose($handle);
        if ($errors) {
            return back()->withErrors(['file' => implode(' | ', $errors)]);
        }
        DB::transaction(function () use ($rows) {
            $role = Role::where('nama_role', 'Siswa')->firstOrFail();
            foreach ($rows as $row) {
                $class = Kelas::where('nama_kelas', $row['nama_kelas'])->firstOrFail();
                $user = User::updateOrCreate(['username' => $row['username']], ['nama_lengkap' => $row['nama_lengkap'], 'password' => $row['password'], 'role_id' => $role->id]);
                Siswa::updateOrCreate(['nisn' => $row['nisn']], ['user_id' => $user->id, 'kelas_id' => $class->id, 'jenis_kelamin' => $row['jenis_kelamin']]);
            }
        });

        return back()->with('success', count($rows).' data siswa berhasil diimpor.');
    }

    public function studentImportTemplate(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['nama_lengkap', 'username', 'password', 'nisn', 'nama_kelas', 'jenis_kelamin']);
            fclose($out);
        }, 'template-import-siswa.csv', ['Content-Type' => 'text/csv']);
    }

    public function exportMasterStudents(Request $request): StreamedResponse
    {
        $students = Siswa::with(['user', 'kelas'])->when($request->filled('q'), fn ($query) => $query->where('nisn', 'like', '%'.$request->q.'%'))->cursor();

        return response()->streamDownload(function () use ($students) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['nama_lengkap', 'username', 'nisn', 'nama_kelas', 'jenis_kelamin']);
            foreach ($students as $student) {
                fputcsv($out, [$student->user->nama_lengkap, $student->user->username, $student->nisn, $student->kelas->nama_kelas, $student->jenis_kelamin]);
            } fclose($out);
        }, 'data-siswa.csv', ['Content-Type' => 'text/csv']);
    }

    public function storeMasterStudent(Request $request)
    {
        $data = $this->validateMasterStudent($request);
        DB::transaction(function () use ($data) {
            $role = Role::where('nama_role', 'Siswa')->firstOrFail();
            $user = User::create([
                'nama_lengkap' => $data['nama_lengkap'], 'username' => $data['username'],
                'password' => $data['password'], 'role_id' => $role->id,
            ]);
            Siswa::create(['user_id' => $user->id, 'kelas_id' => $data['kelas_id'], 'nisn' => $data['nisn'], 'jenis_kelamin' => $data['jenis_kelamin']]);
        });

        return back()->with('success', 'Akun dan profil siswa berhasil ditambahkan.');
    }

    public function updateMasterStudent(Request $request, Siswa $siswa)
    {
        $data = $this->validateMasterStudent($request, $siswa);
        DB::transaction(function () use ($data, $siswa) {
            $userData = ['nama_lengkap' => $data['nama_lengkap'], 'username' => $data['username']];
            if (filled($data['password'] ?? null)) {
                $userData['password'] = $data['password'];
            }
            $siswa->user->update($userData);
            $siswa->update(['kelas_id' => $data['kelas_id'], 'nisn' => $data['nisn'], 'jenis_kelamin' => $data['jenis_kelamin']]);
        });

        return back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroyMasterStudent(Siswa $siswa)
    {
        $hasHistory = $siswa->catatanPoin()->exists()
            || $siswa->laporanKesiswaan()->exists()
            || $siswa->notifikasi()->exists()
            || $siswa->suratPanggilan()->exists();

        if ($hasHistory) {
            DB::transaction(function () use ($siswa) {
                $siswa->update(['status' => 'nonaktif', 'dinonaktifkan_pada' => now()]);
                $siswa->user->tokens()->delete();
            });

            return back()->with('success', 'Siswa dinonaktifkan. Seluruh histori tetap tersimpan.');
        }

        DB::transaction(fn () => $siswa->user->delete());

        return back()->with('success', 'Akun dan profil siswa berhasil dihapus.');
    }

    public function activateMasterStudent(Siswa $siswa)
    {
        $siswa->update(['status' => 'aktif', 'dinonaktifkan_pada' => null]);

        return back()->with('success', 'Akun siswa berhasil diaktifkan kembali.');
    }

    public function masterCategories()
    {
        return view('kesiswaan.master.categories', ['categories' => KategoriPoin::withCount('catatanPoin')->orderBy('jenis')->orderBy('bobot_poin')->paginate(20)]);
    }

    public function storeMasterCategory(Request $request)
    {
        KategoriPoin::create($this->validateMasterCategory($request));

        return back()->with('success', 'Kategori poin berhasil ditambahkan.');
    }

    public function updateMasterCategory(Request $request, KategoriPoin $kategori)
    {
        $kategori->update($this->validateMasterCategory($request));

        return back()->with('success', 'Kategori poin berhasil diperbarui.');
    }

    public function destroyMasterCategory(KategoriPoin $kategori)
    {
        abort_if($kategori->catatanPoin()->exists(), 422, 'Kategori yang sudah digunakan tidak dapat dihapus.');
        $kategori->delete();

        return back()->with('success', 'Kategori poin berhasil dihapus.');
    }

    public function masterThresholds()
    {
        return view('kesiswaan.master.thresholds', ['thresholds' => AturanThreshold::orderBy('poin_batas')->paginate(20)]);
    }

    public function storeMasterThreshold(Request $request)
    {
        AturanThreshold::create($this->validateMasterThreshold($request));

        return back()->with('success', 'Aturan threshold berhasil ditambahkan.');
    }

    public function updateMasterThreshold(Request $request, AturanThreshold $threshold)
    {
        $threshold->update($this->validateMasterThreshold($request, $threshold));

        return back()->with('success', 'Aturan threshold berhasil diperbarui.');
    }

    public function destroyMasterThreshold(AturanThreshold $threshold)
    {
        abort_if($threshold->notifikasi()->exists() || $threshold->suratPanggilan()->exists(), 422, 'Threshold yang memiliki histori tidak dapat dihapus. Nonaktifkan saja aturan ini.');
        $threshold->delete();

        return back()->with('success', 'Aturan threshold berhasil dihapus.');
    }

    private function validateMasterUser(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user)],
            'role_id' => ['required', Rule::exists('roles', 'id')->where(fn ($query) => $query->where('nama_role', '!=', 'Siswa'))],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
        ]);
    }

    private function validateMasterClass(Request $request, ?Kelas $kelas = null): array
    {
        return $request->validate([
            'nama_kelas' => ['required', 'string', 'max:100', Rule::unique('kelas')->ignore($kelas)],
            'wali_kelas_id' => ['required', Rule::unique('kelas', 'wali_kelas_id')->ignore($kelas), Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role_id', Role::where('nama_role', 'Wali Kelas')->select('id')))],
        ]);
    }

    private function validateMasterStudent(Request $request, ?Siswa $siswa = null): array
    {
        return $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($siswa?->user_id)],
            'password' => [$siswa ? 'nullable' : 'required', 'string', 'min:8'],
            'nisn' => ['required', 'string', 'max:20', Rule::unique('siswa')->ignore($siswa)],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'jenis_kelamin' => ['required', 'in:L,P'],
        ]);
    }

    private function validateMasterCategory(Request $request): array
    {
        return $request->validate([
            'jenis' => ['required', 'in:pelanggaran,apresiasi'], 'nama_kategori' => ['required', 'string', 'max:255'],
            'bobot_poin' => ['required', 'integer', 'min:1', 'max:10000'], 'tingkat' => ['required', 'in:ringan,sedang,berat'],
        ]);
    }

    private function validateMasterThreshold(Request $request, ?AturanThreshold $threshold = null): array
    {
        $data = $request->validate([
            'poin_batas' => ['required', 'integer', 'min:1', Rule::unique('aturan_threshold')->ignore($threshold)],
            'level' => ['required', 'in:ringan,sedang,berat'], 'judul_notifikasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:3000'], 'template_surat' => ['nullable', 'string', 'max:3000'],
            'has_surat_panggilan' => ['nullable', 'boolean'], 'is_active' => ['nullable', 'boolean'],
        ]);
        $data['has_surat_panggilan'] = $request->boolean('has_surat_panggilan');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function schoolStats(): array
    {
        $recordCounts = CatatanPoin::where('status_validasi', 'disetujui')
            ->join('kategori_poin', 'kategori_poin.id', '=', 'catatan_poin.kategori_poin_id')
            ->selectRaw("SUM(CASE WHEN kategori_poin.jenis = 'pelanggaran' THEN 1 ELSE 0 END) as violations")
            ->selectRaw("SUM(CASE WHEN kategori_poin.jenis = 'apresiasi' THEN 1 ELSE 0 END) as appreciations")
            ->first();

        return ['violations' => (int) $recordCounts->violations, 'appreciations' => (int) $recordCounts->appreciations, 'pending' => LaporanKesiswaan::where('status', 'pending')->count(), 'attention' => Siswa::where('total_poin_pelanggaran', '>=', 25)->count(), 'alerts' => Notifikasi::where('is_resolved', false)->count()];
    }

    private function studentFor(Request $request): Siswa
    {
        $student = $request->user()->siswa?->load('kelas');
        abort_unless($student, 404, 'Profil siswa belum terhubung.');

        return $student;
    }

    private function homeroomFor(Request $request)
    {
        $kelas = $request->user()->kelas;
        abort_unless($kelas, 404, 'Wali kelas belum memiliki kelas tanggung jawab.');

        return $kelas;
    }
}
