<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
    <title>Atte</title>
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__logo--outer">
                <a href="/" class="header__logo">
                    Atte
                </a>
            </div>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <footer class="footer">
        <div class="footer__inner">
            <small class="footer__copyright">Atte, inc.</small>
        </div>
    </footer>
</body>
</html>