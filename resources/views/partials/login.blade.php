<main class="login-page" data-view="login">
    <section class="login-story">
        <div class="login-story__top">
            <a class="login-brand" href="/" aria-label="POINKU">
                <span class="brand-symbol">P<span>+</span></span>
                <span><strong>POINKU</strong><small>student care system</small></span>
            </a>
            <span class="school-chip">Sistem Kesiswaan</span>
        </div>
        <div class="login-copy">
            <p class="eyebrow light">TERTIB. PEDULI. BERTUMBUH.</p>
            <h1>Setiap catatan adalah awal dari <em>perubahan.</em></h1>
            <p>Satukan pelaporan, pendampingan, dan apresiasi siswa dalam alur yang transparan untuk seluruh warga sekolah.</p>
        </div>
        <div class="threshold-track" aria-label="Ambang penanganan siswa">
            <div><strong>25</strong><span>Pemantauan</span></div>
            <div><strong>50</strong><span>Panggilan orang tua</span></div>
            <div><strong>100</strong><span>Penanganan khusus</span></div>
        </div>
        <p class="login-footnote">Satu sistem, satu riwayat, tindak lanjut yang tepat.</p>
    </section>

    <section class="login-panel">
        <div class="login-form-wrap">
            <span class="mobile-brand"><span class="brand-symbol">P<span>+</span></span> POINKU</span>
            <p class="eyebrow">PORTAL SEKOLAH</p>
            <h2>Selamat datang kembali</h2>
            <p class="lead-muted">Masuk menggunakan akun yang telah diberikan sekolah.</p>
            <form id="login-form" class="login-form">
                <label for="username">Username
                    <span class="input-control"><svg><use href="#icon-user"></use></svg><input id="username" name="username" required autocomplete="username" placeholder="contoh: guru.bk"></span>
                </label>
                <label for="password">Password
                    <span class="input-control"><svg><use href="#icon-lock"></use></svg><input id="password" name="password" required type="password" autocomplete="current-password" placeholder="Masukkan password"><button class="password-toggle" type="button" aria-label="Tampilkan password"><svg><use href="#icon-eye"></use></svg></button></span>
                </label>
                <button class="button primary login-submit" type="submit"><span>Masuk ke dashboard</span><svg><use href="#icon-arrow-right"></use></svg></button>
                <p id="login-error" class="form-error" role="alert"></p>
            </form>
            <p class="login-help">Kesulitan masuk? Hubungi administrator sekolah.</p>
        </div>
    </section>
</main>
