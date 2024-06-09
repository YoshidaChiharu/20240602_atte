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
            <div class="header__nav--outer">
                @if(Auth::check())
                <nav>
                    <ul class="header__nav">
                        <li>
                            <a class="header__nav-item" href="/">ホーム</a>
                        </li>
                        <li>
                            <a class="header__nav-item" href="/attendance">日付一覧</a>
                        </li>
                        <li>
                            <form action="/logout" method="post">
                                @csrf
                                <button class="header__nav-item">ログアウト</button>
                            </form>
                        </li>
                    </ul>
                </nav>
                @endif
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