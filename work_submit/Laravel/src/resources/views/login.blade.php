<html>

<head>
    <title>ログイン画面</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <script src="{{ asset('/js/password.js') }}"></script>
</head>

<body>
    <div id="container" class="container mt-5" style="max-width: 400px;">
        <p class="display-6">ログイン画面</p>
        <form method="post" action="{{ route('post.login') }}">
            @csrf
            <label class="input_label" for="email">メールアドレス</label>
            <br>
            <input class="form-control" style="margin-bottom:20px;" id="email" type="email" name="email"
                value="{{ old('email') }}" required>
            <br>
            <label class="input_label" for="password">パスワード</label>
            <br>
            <div class="input-group mb-3">
                <input class="form-control" id="password" type="password" name="password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                </button>
            </div>
            <br>
            <input class="btn btn-primary" type="submit" value="ログイン">
        </form>
        <a class="regist_link" href="{{ route('registration.index') }}">会員登録がまだの方はコチラ</a>
        <br>
        <a class="regist_link" href="{{ route('reset.form') }}">パスワードを忘れた方はコチラ</a>
    </div>
    
</body>

</html>