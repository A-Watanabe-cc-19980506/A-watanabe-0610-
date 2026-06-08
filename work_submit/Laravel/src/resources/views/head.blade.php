<html>

<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
</head>

<body>
    <div class="header_list product_header_list">
        <div class="start-section">
            <a href="{{ route('user.index') }}"><img src="{{ asset('img/logo.jpg')}}" alt="ロゴ画像" class="logo"></a>
        </div>
        <div class="center-section">
        </div>
        <!--ログイン後表示-->
        @auth
            <div class="end-section">
                <ul class="header_ul">
                    <li class="header_li">
                        <form class="logout_form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <input type="submit" name="tab_item" id="logout_input">
                            <label for="logout_input" class="logout_a">ログアウト</label>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
        <!--ログアウト後表示-->
        @guest
            <div class="end-section">
                <ul class="header_ul">
                    <li class="header_li"><a class="li_a" href="{{ route('login') }}">ログイン</a></li>
                    <li class="header_li"><a class="li_a" href="{{ route('registration.index') }}">会員登録</a></li>
                </ul>
            </div>
        @endguest
    </div>
</body>

</html>