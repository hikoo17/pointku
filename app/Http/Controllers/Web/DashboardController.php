<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ApprovalLaporan;
use App\Models\CatatanPoin;
use App\Models\KategoriPoin;
use App\Models\LaporanKesiswaan;
use App\Models\Notifikasi;
use App\Models\Siswa;
use App\Models\SuratPanggilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function redirect(Request $request)
    {
        return redirect()->route(match ($request->user()->role->nama_role) {
            'Kesiswaan' => 'kesiswaan.dashboard',
            'Guru BK', 'Guru Pelapor' => 'guru.dashboard',
            'Wali Kelas' => 'wali-kelas.dashboard',
            'Siswa' => 'siswa.dashboard',
            default => 'login',
        });
    }

    public function kesiswaan()
    {
        return view('dashboards.kesiswaan', [
            'stats' => $this->schoolStats(),
            'reports' => LaporanKesiswaan::with(['siswa.user', 'bk'])->latest('diajukan_pada')->limit(6)->get(),
            'students' => Siswa::with(['user', 'kelas'])->where('total_poin_pelanggaran', '>=', 25)->orderByDesc('total_poin_pelanggaran')->limit(6)->get(),
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
        if ($request->status === 'dipantau') $query->where('total_poin_pelanggaran', '>=', 25);
        if ($request->status === 'normal') $query->where('total_poin_pelanggaran', '<', 25);
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
        if (! $notifikasi->dibaca_pada) $notifikasi->update(['dibaca_pada' => now()]);
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
        if ($request->filled('dari')) $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $query->whereDate('tanggal', '<=', $request->sampai);
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
        if (! $notifikasi->dibaca_pada) $notifikasi->update(['dibaca_pada' => now()]);
        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function records(Request $request)
    {
        $query = CatatanPoin::with(['siswa.user', 'siswa.kelas', 'kategoriPoin', 'pencatat']);
        if ($request->user()->hasRole('Guru Pelapor')) $query->where('pencatat_id', $request->user()->id);
        if ($request->filled('q')) $query->whereHas('siswa.user', fn ($q) => $q->where('nama_lengkap', 'like', '%'.$request->q.'%'));

        return view('guru.records', [
            'records' => $query->latest()->paginate(15)->withQueryString(),
            'students' => Siswa::with(['user', 'kelas'])->orderBy('nisn')->get(),
            'categories' => KategoriPoin::orderBy('jenis')->orderBy('bobot_poin')->get(),
        ]);
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
            'bukti_foto' => ['nullable', 'image', 'max:2048'], 'status_validasi' => ['nullable', 'in:draft,disetujui'],
        ]);
        $data['pencatat_id'] = $request->user()->id;
        $data['status_validasi'] = $request->user()->hasRole('Guru Pelapor') ? 'menunggu_validasi' : ($data['status_validasi'] ?? 'disetujui');
        if ($request->hasFile('bukti_foto')) $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti-poin', 'public');
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

    public function statistics()
    {
        $classes = DB::table('kelas')->leftJoin('siswa', 'kelas.id', '=', 'siswa.kelas_id')->select('kelas.nama_kelas', DB::raw('COUNT(siswa.id) as total_siswa'), DB::raw('COALESCE(SUM(siswa.total_poin_pelanggaran), 0) as pelanggaran'), DB::raw('COALESCE(SUM(siswa.total_poin_apresiasi), 0) as apresiasi'))->groupBy('kelas.id', 'kelas.nama_kelas')->get();
        return view('kesiswaan.statistics', ['stats' => $this->schoolStats(), 'classes' => $classes]);
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

    private function schoolStats(): array
    {
        return ['violations' => Siswa::sum('total_poin_pelanggaran'), 'appreciations' => Siswa::sum('total_poin_apresiasi'), 'pending' => LaporanKesiswaan::where('status', 'pending')->count(), 'attention' => Siswa::where('total_poin_pelanggaran', '>=', 25)->count(), 'alerts' => Notifikasi::where('is_resolved', false)->count()];
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
