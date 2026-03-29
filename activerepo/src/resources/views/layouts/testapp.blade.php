<!-- resources/views/layouts/testapp.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>テスト掲示板風</title>
    <link rel="stylesheet" href="{{ asset('css/test.css') }}">
</head>
<body>
<div class="container">
    <header>
        <h1>テスト掲示板</h1>
        <nav class="navbar">
            <a href="{{ route('test.index') }}">掲示板トップ</a> |
            <a href="#new-post">新規書き込み</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>テスト掲示板</p>
    </footer>
</div>
</body>
</html>
