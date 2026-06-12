<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>top画面</title>
</head>

<body style="margin: 0; padding: 0; font-family: sans-serif; background-color: #f9f9f9;">
    <a href="{{ route('products.index') }}">商品一覧検索へ</a>
    <br>
    <a href="{{ route('login') }}">ログイン画面へ</a>
    <form class="logout_form" method="POST" action="{{ route('logout') }}">
        @csrf
        <input type="submit" name="tab_item" id="logout_input">
        <label for="logout_input" class="logout_a">ログアウト</label>
    </form>
</body>

</html>