<html>

<head>
    <title>会員登録画面</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <script src="{{ asset('/js/password.js') }}"></script>
    </head>

<body>
    <div id="container" class="container mt-5" style="max-width: 400px;">
        <p class="display-6">会員登録画面</p>
        <form method="POST" action="{{ route('registration.confirm') }}">
            @csrf
            <div class="row mb-3">
                {{-- ミョウジ --}}
                <div class="col-6">
                    <div class="div_w_95">
                        <label class="input_label">セイ</label>
                        <input class="form-control" type="text"
                            value="{{ old('last_name_kana', $form['last_name_kana'] ?? '') }}" name="last_name_kana"
                            id="last_name_kana" maxlength="30" required>
                        @error('last_name_kana') <li class="error_msg">{{ $message }}</li> @enderror
                    </div>
                </div>
                {{-- ナマエ --}}
                <div class="col-6">
                    <div class="div_w_5">
                        <label class="input_label" for="first_name_kana">メイ</label>
                        <input class="form-control" type="text"
                            value="{{ old('first_name_kana', $form['first_name_kana'] ?? '') }}" name="first_name_kana"
                            id="first_name_kana" maxlength="30" required>
                        @error('first_name_kana') <li class="error_msg">{{ $message }}</li> @enderror
                    </div>
                </div>
            </div>
            {{-- 苗字 --}}
            <div class="row mb-3">
                <div class="col-6">
                    <div class="div_w_95">
                        <label class="input_label" for="last_name_kanji">姓</label>
                        <input class="form-control" type="text"
                            value="{{ old('last_name_kanji', $form['last_name_kanji'] ?? '') }}" name="last_name_kanji"
                            id="last_name_kanji" maxlength="30" required>
                        @error('last_name_kanji') <li class="error_msg">{{ $message }}</li> @enderror
                    </div>
                </div>
                {{-- 名前 --}}
                <div class="col-6">
                    <div class="div_w_95">
                        <label class="input_label" for="first_name_kanji">名</label>
                        <input class="form-control" type="text"
                            value="{{ old('first_name_kanji', $form['first_name_kanji'] ?? '') }}"
                            name="first_name_kanji" id="first_name_kanji" maxlength="30" required>
                        @error('first_name_kanji') <li class="error_msg">{{ $message }}</li> @enderror
                    </div>
                </div>
            </div>
            {{-- メールアドレス --}}
            <label class="input_label" for="email">メールアドレス</label>
            <br>
            <input class="form-control w-100" id="email" type="email" value="{{ old('email', $form['email'] ?? '') }}"
                name="email" required>
            <br>
            @error('email') <li class="error_msg">{{ $message }}</li> @enderror
            {{-- パスワード --}}
            <label class="input_label" for="password">パスワード</label>
            <br>
            <div class="input-group mb-3">
                <input class="form-control" id="password" type="password" name="password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                </button>
            </div>
            <br>
            @error('password') <li class="error_msg">{{ $message }}</li> @enderror
            {{-- 確認ボタン --}}
            <input class="btn btn-primary w-100 mt-4" type="submit">
        </form>
        <div>
            <a href="https://www.google.com" target="_blank" rel="noopener noreferrer">
                <img src="google-icon.png" alt="Google" width="30" height="30"> Google
            </a>

            <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer">
                <img src="facebook-icon.png" alt="Facebook" width="30" height="30"> Facebook
            </a>
        </div>
    </div>
</body>

</html>