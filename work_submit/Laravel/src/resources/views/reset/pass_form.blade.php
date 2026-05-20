<main>
    <h2>パスワード再設定</h2>
    <form method="POST" action="{{ route('reset.password.update') }}">
        @csrf
        <input type="hidden" name="reset_token" value="{{ $userToken->rest_password_access_key }}">
        <div>
            <label>新パスワード</label>
            <input type="password" name="password" value="">
            <span>{{ $errors->first('password') }}</span>
            <span>{{ $errors->first('reset_token') }}</span>
        </div>
        <div>
            <label>新パスワード<span>確認</span></label>
            <input type="password" name="password_confirmation" value="">
        </div>
        <div>
            <button type="submit">パスワードを再設定する</button>
        </div>
    </form>
</main>