<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ asset('/js/search.js') }}"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>商品検索</title>
</head>

<body>
    <div class="card shadow-sm border-0 bg-light p-4">
        <h5 class="font-weight-bold text-dark mb-4 border-bottom pb-2">
            <i class="fas fa-search mr-1 text-muted"></i> 条件から探す
        </h5>

        <form action="{{ route('products.index') }}" method="GET" id="search-form">

            <div class="form-group mb-4">
                <label for="keyword" class="font-weight-bold small text-secondary mb-1">キーワード</label>
                <div class="input-group">
                    <input type="text" name="keyword" id="keyword"
                        class="form-control form-control-sm border-secondary-subtle" value="{{ request('keyword') }}"
                        placeholder="商品名を入力">
                </div>
            </div>

            <div class="form-group mb-4">
                <label for="category_id" class="font-weight-bold small text-secondary mb-1">カテゴリー</label>
                <select name="category_id" id="category_id"
                    class="form-control form-control-sm custom-select custom-select-sm border-secondary-subtle">
                    <option value="">指定なし</option>
                    @foreach($categories as $id => $name)
                        <option value="{{ $id }}" {{ request('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="color" class="font-weight-bold small text-secondary mb-1">カラー</label>
                <select name="color" id="color"
                    class="form-control form-control-sm custom-select custom-select-sm border-secondary-subtle">
                    <option value="">指定なし</option>
                    @foreach($colors as $id => $name)
                        <option value="{{ $id }}" {{ request('color') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="size" class="font-weight-bold small text-secondary mb-1">サイズ</label>
                <select name="size" id="size"
                    class="form-control form-control-sm custom-select custom-select-sm border-secondary-subtle">
                    <option value="">指定なし</option>
                    @foreach($sizes as $id => $name)
                        <option value="{{ $id }}" {{ request('size') == 'size' ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold small text-secondary mb-2 d-block">価格</label>

                <div class="custom-control custom-radio mb-2">
                    <input type="radio" id="price_all" name="price_range" value="" class="custom-control-input" {{ !request('price_range') ? 'checked' : '' }}>
                    <label class="custom-control-label small text-dark" href="#" for="price_all">指定なし</label>
                </div>

                <div class="custom-control custom-radio mb-2">
                    <input type="radio" id="price_under_1000" name="price_range" value="under_1000"
                        class="custom-control-input" {{ request('price_range') == 'under_1000' ? 'checked' : '' }}>
                    <label class="custom-control-label small text-dark" for="price_under_1000">1,000円以下</label>
                </div>

                <div class="custom-control custom-radio mb-2">
                    <input type="radio" id="price_1000_to_3000" name="price_range" value="1000_to_3000"
                        class="custom-control-input" {{ request('price_range') == '1000_to_3000' ? 'checked' : '' }}>
                    <label class="custom-control-label small text-dark" for="price_1000_to_3000">1,001円〜3,000円</label>
                </div>

                <div class="custom-control custom-radio mb-3">
                    <input type="radio" id="price_over_3000" name="price_range" value="over_3000"
                        class="custom-control-input" {{ request('price_range') == 'over_3000' ? 'checked' : '' }}>
                    <label class="custom-control-label small text-dark" for="price_over_3000">3,001円以上</label>
                </div>
            </div>

            <button type="submit" class="btn btn-dark btn-sm btn-block font-weight-bold shadow-sm py-2 mt-2">
                この条件で検索
            </button>

        </form>
    </div>
</body>

</html>