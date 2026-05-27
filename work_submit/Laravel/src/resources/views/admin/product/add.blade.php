<html>
  <head>
    <title>商品追加</title>
    <link rel="stylesheet" href="{{ asset('/css/admin_product.css') }}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
  </head>
  <body>
    <div id="container">
      <form method="post" action="" enctype="multipart/form-data">
        {{ csrf_field() }}
        <label class="input_label" for="category_id">カテゴリ</label>
        <br>
        <select class="text_input_w100" name="category_id" required>
        </select>
        <br>
        <label class="input_label" for="name">商品名</label>
        <br>
        <input class="text_input_w100" type="text" name="name" required>
        <br>
        <label class="input_label" for="price">価格</label>
        <br>
        <input class="text_input_w100" type="number" name="price" required>
        <br>
        <label class="input_label" for="image_path">商品画像</label>
        <br>
        <input type="file" id="file" name="image_path" class="form-control">
        <br>
        <input class="submit_btn" type="submit" value="登録">
      </form>
      <div class="back_btn">
        <a href="">戻る</a>
      </div>  
    </div>
  </body>
</html>