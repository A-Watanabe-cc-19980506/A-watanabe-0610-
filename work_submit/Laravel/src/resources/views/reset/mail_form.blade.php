<html>

<head>
    <title>登録情報確認画面</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
</head>

<body>
    <div class="container mt-5" style="max-width: 400px;">
        <h2>パスワード再設定</h2>
        <p>ご利用中のメールアドレスを入力してください</p>
        <p>パスワード再設定のためのURLをお送りします</p>
        <form method="POST" action="{{ route('reset.send') }}">
            @csrf
            <div>
                <label>メールアドレス</label>
                <input class="form-control" type="text" name="email" value="{{ old('email') }}">
                <span>{{ $errors->first('email') }}</span>
            </div>
            <div>
                <button type="submit" class="btn btn-primary w-100 mt-4">再設定メールを送信</button>
                <br>
                <a href="{{ route('login') }}" class="btn btn-danger w-100 text-white text-decoration-none mb-4" >戻る</a>
            </div>
        </form>
    </div>
</body>

</html>