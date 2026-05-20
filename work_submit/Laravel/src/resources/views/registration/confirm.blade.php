<html>

<head>
    <title>登録情報確認</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
</head>

<body>
    <div id="container">
        <p class="title">以下の内容で登録します。お間違いないですか？</p>
        <form method="POST" action="{{ route('registration.completed') }}">
            @csrf
            <div class="input_row">
                {{-- ミョウジ --}}
                <div class="input_part">
                    <div class="div_w_95">
                        <label class="input_label" for="last_name_kana">セイ</label>
                        <input class="text_input" value="{{ $form['last_name_kana'] }}" type="text"
                            name="last_name_kana" id="last_name_kana" readonly>
                    </div>
                    <div class="div_w_5">
                    </div>
                </div>
                {{-- ナマエ --}}
                <div class="input_part">
                    <div class="div_w_5">
                    </div>
                    <div class="div_w_95">
                        <label class="input_label" for="first_name_kana">メイ</label>
                        <input class="text_input" value="{{ $form['first_name_kana'] }}" type="text"
                            name="first_name_kana" id="first_name_kana" readonly>
                    </div>
                </div>
            </div>
            <div class="input_row">
                {{-- 苗字 --}}
                <div class="input_part">
                    <div class="div_w_95">
                        <label class="input_label" for="last_name_kanji">姓</label>
                        <input class="text_input" value="{{ $form['last_name_kanji'] }}" type="text"
                            name="last_name_kanji" id="last_name_kanji" readonly>
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
                        <br>
                        <input class="text_input" value="{{ $form['first_name_kanji'] }}" type="text"
                            name="first_name_kanji" id="first_name_kanji" readonly>
                    </div>
                </div>
            </div>
            {{-- メールアドレス --}}
            <label class="input_label" for="email">メールアドレス</label>
            <br>
            <input class="text_input_w100" value="{{ $form['email'] }}" id="email" type="email" name="email" readonly>
            <input class="submit_btn" type="submit" value="登録">
        </form>
        <div class="back_btn">
            <a href="{{ route('registration.index') }}">戻る</a>
        </div>
    </div>
</body>

</html>