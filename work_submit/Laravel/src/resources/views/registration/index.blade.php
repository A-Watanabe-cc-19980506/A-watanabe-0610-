<html>

<head>
    <title>新規会員登録</title>
    <link rel="stylesheet" href="{{ asset('css/registration.css') }}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
</head>

<body>
    <div id="container">
        <p class="title">会員登録</p>
        <form method="POST" action="{{ route('registration.confirm') }}">
            @csrf
            <div class="input_row">
                {{-- ミョウジ --}}
                <div class="input_part">
                    <div class="div_w_95">
                        <label class="input_label" for="last_name_kana">セイ</label>
                        <input class="text_input" type="text"
                            value="{{ old('last_name_kana', $form['last_name_kana'] ?? '') }}" name="last_name_kana"
                            id="last_name_kana" maxlength="30" required>
                        @error('last_name_kana') <li class="error_msg">{{ $message }}</li> @enderror
                    </div>
                    <div class="div_w_5">
                    </div>
                </div>
                {{-- ナマエ --}}
                <div class="input_part">
                    <div class="div_w_5"></div>
                    <div class="div_w_95">
                        <label class="input_label" for="first_name_kana">メイ</label>
                        <input class="text_input" type="text"
                            value="{{ old('first_name_kana', $form['first_name_kana'] ?? '') }}" name="first_name_kana"
                            id="first_name_kana" maxlength="30" required>
                        @error('first_name_kana') <li class="error_msg">{{ $message }}</li> @enderror
                    </div>
                </div>
            </div>
            {{-- 苗字 --}}
            <div class="input_row">
                <div class="input_part">
                    <div class="div_w_95">
                        <label class="input_label" for="last_name_kanji">姓</label>
                        <input class="text_input" type="text"
                            value="{{ old('last_name_kanji', $form['last_name_kanji'] ?? '') }}" name="last_name_kanji"
                            id="last_name_kanji" maxlength="30" required>
                        @error('last_name_kanji') <li class="error_msg">{{ $message }}</li> @enderror
                    </div>
                    <div class="div_w_5">
                    </div>
                </div>
                {{-- 名前 --}}
                <div class="input_part">
                    <div class="div_w_5">
                    </div>
                    <div class="div_w_95">
                        <label class="input_label" for="first_name_kanji">名</label>
                        <input class="text_input" type="text"
                            value="{{ old('first_name_kanji', $form['first_name_kanji'] ?? '') }}"
                            name="first_name_kanji" id="first_name_kanji" maxlength="30" required>
                        @error('first_name_kanji') <li class="error_msg">{{ $message }}</li> @enderror
                    </div>
                </div>
            </div>
            {{-- メールアドレス --}}
            <label class="input_label" for="email">メールアドレス</label>
            <br>
            <input class="text_input_w100" id="email" type="email" value="{{ old('email', $form['email'] ?? '') }}"
                name="email" required>
            <br>
            @error('email') <li class="error_msg">{{ $message }}</li> @enderror
            {{-- パスワード --}}
            <label class="input_label" for="password">パスワード</label>
            <br>
            <input class="text_input_w100" id="password" type="password" name="password" required>
            <br>
            @error('password') <li class="error_msg">{{ $message }}</li> @enderror
            {{-- 確認ボタン --}}
            <input class="submit_btn" type="submit">
        </form>
    </div>
</body>

</html>