<!DOCTYPE html>
<html>
<head>
    <title>Surat Panggilan Orang Tua</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin-bottom: 20px; }
        .signature { margin-top: 50px; }
        .print-button { position: fixed; top: 16px; right: 16px; border: 0; border-radius: 8px; padding: 10px 16px; background: #6d1a1a; color: white; font-weight: bold; cursor: pointer; }
        @media print { .print-button { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
    <button class="print-button" type="button" onclick="window.print()">Cetak surat</button>
    <div class="header">
        <h2>SURAT PANGGILAN ORANG TUA/WALI</h2>
        <p>Nomor: {{ $surat->nomor_surat }}</p>
        <p>Tanggal: {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d F Y') }}</p>
    </div>

    <div class="content">
        <p>Kepada Yth.<br>
        Orang Tua/Wali dari: {{ $surat->siswa->user->nama_lengkap }}<br>
        NISN: {{ $surat->siswa->nisn }}<br>
        Kelas: {{ $surat->siswa->kelas->nama_kelas ?? '-' }}
        </p>

        <p>Dengan hormat,</p>

        <p>Berdasarkan kondisi dan perilaku peserta didik selama ini, kami menemukan bahwa peserta didik tersebut telah melanggar aturan sekolah. Berikut adalah keterangan lebih lanjut:</p>

        <p><strong>Alasan Pemanggilan:</strong><br>
        {{ $surat->alasan_pemanggilan }}
        </p>

        <p><strong>Total Poin Pelanggaran:</strong> {{ $surat->total_poin }}</p>

        <p><strong>Daftar Kejadian:</strong><br>
        {{ $surat->daftar_kejadian ?? '-' }}
        </p>

        <p><strong>Tindakan yang Direkomendasikan:</strong><br>
        {{ $surat->tindakan_direkomendasikan }}
        </p>

        <p>Demikian surat ini kami sampaikan agar dapat segera dihadapi dengan serius dan melakukan perbaikan perilaku.</p>
    </div>

    <div class="signature">
        <p>__________________________</p>
        <p>Guru BK / Kesiswaan</p>
        <p>{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
    </div>
</body>
</html>
