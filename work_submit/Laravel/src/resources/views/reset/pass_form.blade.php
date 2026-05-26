<html>

<head>
    <title>パスワード再設定</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <script src="{{ asset('/js/password.js') }}" defer></script>
</head>

<body>
    <div class="container mt-5" style="max-width: 400px;">
        <h2>パスワード再設定</h2>
        <form method="POST" action="{{ route('reset.password.update') }}">
            @csrf
            <input type="hidden" name="reset_token" value="{{ $userToken->rest_password_access_key }}">
            <div>
                <label>新パスワード</label>
                <div class="input-group mb-3">
                <input class="form-control" id="password" type="password" name="password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                </button>
            </div>
                <span>{{ $errors->first('password') }}</span>
                <span>{{ $errors->first('reset_token') }}</span>
            </div>
            <div>
                <label>新パスワード<span>確認</span></label>
                <div class="input-group mb-3">
                <input class="form-control" id="confirmPassword" type="password" name="password_confirmation" required>
                <button class="btn btn-outline-secondary" type="button" id="confirmToggleBtn">
                    <i class="bi bi-eye-slash" id="confirmToggleIcon"></i>
                </button>
            </div>
            <div>
                <button class="btn btn-primary mb-5" type="submit">パスワードを再設定する</button>
            </div>
        </form>
    </div>
</body>

</html>