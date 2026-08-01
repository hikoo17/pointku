@props(['title' => 'Dashboard', 'navigation' => []])
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | POINKU</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="sidebar-head">
                <a class="brand" href="{{ route('dashboard') }}">
                    <span class="brand-symbol">P<span>+</span></span>
                    <span><strong>POINKU</strong><small>student care system</small></span>
                </a>
            </div>
            <p class="nav-label">RUANG KERJA</p>
            <nav class="primary-nav">
                @foreach ($navigation as $item)
                    <a class="nav-item {{ request()->routeIs($item[0]) ? 'active' : '' }}" href="{{ route($item[0]) }}">
                        <span>{{ $item[1] }}</span>
                        <i class="nav-indicator"></i>
                    </a>
                @endforeach
            </nav>
            <div class="sidebar-note">
                <div><strong>Budaya positif</strong><span>Catat kebaikan sekecil apa pun.</span></div>
            </div>
            <div class="sidebar-profile">
                <span class="avatar">{{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}</span>
                <span class="profile-copy">
                    <strong>{{ auth()->user()->nama_lengkap }}</strong>
                    <small>{{ auth()->user()->role->nama_role }}</small>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-button" aria-label="Keluar">Keluar</button>
                </form>
            </div>
        </aside>
        <main class="main-content">
            <header class="topbar">
                <div><span>POINKU / </span><strong>{{ $title }}</strong></div>
                <div class="topbar-actions">
                    <span class="today-label">{{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="top-avatar">{{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}</span>
                </div>
            </header>
            <section class="page-content">
                @if (session('success'))
                    <div class="flash success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="flash error">{{ $errors->first() }}</div>
                @endif
                {{ $slot }}
            </section>
        </main>
    </div>
</body>
</html>
