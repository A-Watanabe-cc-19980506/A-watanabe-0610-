<html>

<head>
    <title>パスワード変更完了</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
</head>

<body>
    <div class="container mt-5" style="max-width: 400px;">
        <h2>パスワード変更完了</h2>
        <div>
            <p>パスワードの変更が完了しました</p>
            <p>新しいパスワードにて再ログインしてください</p>
        </div>
        <div>
            <a class="btn btn-primary" href="{{ route('login') }}">ログイン画面へ</a>
        </div>
    </div>
</body>

</html>