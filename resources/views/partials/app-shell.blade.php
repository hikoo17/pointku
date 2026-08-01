<div class="app-shell is-hidden" data-view="application">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-head">
            <a class="brand" href="#overview" data-nav="overview">
                <span class="brand-symbol">P<span>+</span></span>
                <span><strong>POINKU</strong><small>student care system</small></span>
            </a>
            <button class="sidebar-close" type="button" aria-label="Tutup menu"><svg><use href="#icon-close"></use></svg></button>
        </div>
        <p class="nav-label">RUANG KERJA</p>
        <nav id="primary-nav" class="primary-nav" aria-label="Navigasi utama"></nav>
        <div class="sidebar-note">
            <svg><use href="#icon-heart"></use></svg>
            <div><strong>Budaya positif</strong><span>Catat kebaikan sekecil apa pun.</span></div>
        </div>
        <div class="sidebar-profile">
            <span class="avatar" data-user-initial>P</span>
            <span class="profile-copy"><strong data-user-name>Pengguna</strong><small data-user-role>Peran</small></span>
            <button id="logout" class="logout-button" type="button" aria-label="Keluar"><svg><use href="#icon-logout"></use></svg></button>
        </div>
    </aside>
    <button class="sidebar-scrim" type="button" aria-label="Tutup menu"></button>

    <main class="main-content">
        <header class="topbar">
            <div class="topbar-title">
                <button class="menu-button" type="button" aria-label="Buka menu"><svg><use href="#icon-menu"></use></svg></button>
                <div><span>POINKU /</span><strong id="page-breadcrumb">Ringkasan</strong></div>
            </div>
            <div class="topbar-actions">
                <span class="today-label" id="today-label"></span>
                <button class="notification-button" type="button" data-nav="alerts" aria-label="Notifikasi"><svg><use href="#icon-bell"></use></svg><span id="notification-count" class="notification-count is-hidden">0</span></button>
                <span class="top-avatar" data-user-initial>P</span>
            </div>
        </header>
        <section id="page-content" class="page-content" aria-live="polite">
            @include('partials.loading')
        </section>
    </main>
</div>

<div id="modal-root"></div>
