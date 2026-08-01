@php($title = 'Dashboard Saya')
@php($navigation = [['siswa.dashboard', 'Ringkasan'], ['siswa.history', 'Riwayat poin'], ['siswa.notifications', 'Notifikasi']])
<x-layouts.app :title="$title" :navigation="$navigation">
    <x-dashboard title="Halo, {{ auth()->user()->nama_lengkap }}" eyebrow="RUANG SISWA" copy="Lihat perkembangan kedisiplinan, apresiasi, dan tindak lanjut yang tercatat untukmu." />

    <div class="student-status-card {{ $status[1] }}">
        <div>
            <p class="eyebrow">STATUS KEDISIPLINAN</p>
            <h2>{{ $status[0] }}</h2>
            <p>{{ $student->total_poin_pelanggaran < $status[2] ? $status[2] - $student->total_poin_pelanggaran : 0 }} poin menuju ambang berikutnya.</p>
        </div>
        <strong>{{ $student->total_poin_pelanggaran }}<small> poin pelanggaran</small></strong>
    </div>

    <div class="stats-grid">
        <article class="stat-card coral"><span>Pelanggaran</span><strong>{{ $student->total_poin_pelanggaran }}</strong><small>Poin yang telah disetujui</small></article>
        <article class="stat-card mint"><span>Apresiasi</span><strong>{{ $student->total_poin_apresiasi }}</strong><small>Poin positif</small></article>
        <article class="stat-card navy"><span>Saldo poin</span><strong>{{ $student->saldo_poin }}</strong><small>Pelanggaran dan apresiasi</small></article>
        <article class="stat-card yellow"><span>Kelas</span><strong class="class-value">{{ $student->kelas->nama_kelas ?? '-' }}</strong><small>NISN {{ $student->nisn }}</small></article>
    </div>

    <div class="split-panels">
        <section class="panel">
            <div class="panel-heading"><div><p class="eyebrow">AKTIVITAS TERBARU</p><h3>Riwayat poin</h3></div><a class="text-link" href="{{ route('siswa.history') }}">Lihat semua</a></div>
            @forelse ($records as $record)
                <a class="student-activity" href="{{ route('siswa.history.show', $record) }}">
                    <span class="activity-mark {{ $record->kategoriPoin->jenis }}">{{ $record->kategoriPoin->jenis === 'apresiasi' ? '+' : '-' }}</span>
                    <span><strong>{{ $record->kategoriPoin->nama_kategori }}</strong><small>{{ $record->tanggal->translatedFormat('d M Y') }}</small></span>
                    <b>{{ $record->kategoriPoin->bobot_poin }} poin</b>
                </a>
            @empty
                <p class="muted">Belum ada riwayat poin yang disetujui.</p>
            @endforelse
        </section>
        <section class="panel">
            <div class="panel-heading"><div><p class="eyebrow">TINDAK LANJUT</p><h3>Notifikasi saya</h3></div><a class="text-link" href="{{ route('siswa.notifications') }}">Lihat semua</a></div>
            @forelse ($alerts as $alert)
                <div class="student-notice {{ $alert->dibaca_pada ? 'read' : '' }}"><strong>{{ $alert->judul }}</strong><p>{{ $alert->pesan }}</p><small>{{ $alert->created_at->diffForHumans() }}</small></div>
            @empty
                <p class="muted">Belum ada peringatan atau tindak lanjut.</p>
            @endforelse
        </section>
    </div>
</x-layouts.app>
