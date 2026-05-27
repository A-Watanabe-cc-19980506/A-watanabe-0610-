<html>
  <head>
    <title>管理画面トップ</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  </head>
  <body>
    <div id="container">
      <div class="btns">
        <a class="btn_product" href="{{ route('admin.product.add') }}">商品登録</a>
      </div>  
      <div class="btns">
        <a class="btn_product" href="">商品一覧</a>
      </div>  
      <div class="btns">
        <a class="btn_notification" href="">お知らせ登録</a>
      </div>  
      <div class="btns">
        <a class="btn_notification" href="">お知らせ一覧</a>
      </div>  
      <div class="btns">
        <a class="btn_user_index" href="">ユーザー画面</a>
      </div>  
    </div>
  </body>
</html>