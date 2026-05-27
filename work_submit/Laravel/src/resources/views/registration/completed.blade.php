<html>

<head>
    <title>登録完了</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
</head>

<body>
    <div id="container" class="container mt-5" style="max-width: 400px;">
        <p class="title">ユーザー登録が完了しました。</p>
        <a href="{{ route('user.index') }}" class="btn btn-primary w-100 mt-4">トップへ戻る</a>
        <br>
        <a href="{{ route('login') }}" class="btn btn-danger w-100 text-white text-decoration-none mb-4" >ログイン</a>
    </div>

</body>