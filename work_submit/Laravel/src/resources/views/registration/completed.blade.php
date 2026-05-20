<html>

<head>
    <title>登録完了</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
</head>

<body>
    <div id="container">
        <p class="title">ユーザー登録が完了しました。</p>
        <div class="top_btn">
            <a href="{{ route('user.index') }}">トップへ戻る</a>
        </div>
        <div class="login_btn">
          <a href="{{ route('login') }}">ログイン</a>
        </div>
    </div>

</body>