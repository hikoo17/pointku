<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#123c3a">
    <title>POINKU | Sistem Kesiswaan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        @include('partials.login')
        @include('partials.app-shell')
    </div>
    @include('partials.icons')
    <div id="toast-region" class="toast-region" aria-live="polite"></div>
</body>
</html>
