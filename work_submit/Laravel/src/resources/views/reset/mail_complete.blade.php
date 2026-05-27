<html>

<head>
    <title>メール送信完了</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
</head>

<body>
    <div class="container mt-5" style="max-width: 400px;">
        <h2>メール送信完了</h2>
        <div>
            <p>パスワード再設定用のメールを送信しました</p>
            <p>メールに記載されているリンクからパスワードの再設定を行ってください</p>
        </div>
        <div>
            <a href="{{ route('login') }}" class="btn btn-danger w-100 text-white text-decoration-none mb-4">ログイン画面へ</a>
        </div>
    </div>
</body>

</html>