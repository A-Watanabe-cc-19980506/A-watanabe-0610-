<html>

<head>
    <title>ログイン</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
</head>

<body>
    <div id="container">
        <p class="title">ログイン</p>
        @error('login')
            <li>{{ $message }}</li>
        @enderror
        <form method="post" action="{{ route('post.login') }}">
            @csrf
            <label class="input_label" for="email">メールアドレス</label>
            <br>
            <input class="text_input" style="margin-bottom:20px;" id="email" type="email" name="email"
                value="{{ old('email') }}" required>
            <br>
            <label class="input_label" for="password">パスワード</label>
            <br>
            <input class="text_input" id="password" type="password" name="password" required>
            <br>
            <input class="submit_btn" type="submit" value="ログイン">
        </form>
        <a class="regist_link" href="{{ route('registration.index') }}">会員登録がまだの方はコチラ</a>
        <br>
        <a class="regist_link" href="{{ route('reset.form') }}">パスワードを忘れた方はコチラ</a>
    </div>
</body>

</html>