<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
</head>

<body>
{{ Form::open(['route'=>'search','method'=>'GET','id'=>'side_search_form','autocomplete'=>"off"]) }}

    <div class="search_section">
        <input name="keyword" value="{{ request('keyword') }}" type="search" placeholder="キーワード検索">
    </div>

    <div class="search_section">
        <h4>カテゴリ</h4>
        @foreach (config('search_options.categories') as $category)
            <label>
                <input type="checkbox" name="categories[]" value="{{ $category }}" 
                    {{ in_array($category, request('categories', [])) ? 'checked' : '' }}>
                {{ $category }}
            </label>
        @endforeach
    </div>

    <div class="search_section">
        <h4>サイズ</h4>
        @foreach (config('search_options.sizes') as $size)
            <label>
                <input type="checkbox" name="sizes[]" value="{{ $size }}" 
                    {{ in_array($size, request('sizes', [])) ? 'checked' : '' }}>
                {{ $size }}
            </label>
        @endforeach
    </div>

    <div class="search_section">
        <h4>カラー</h4>
        @foreach (config('search_options.colors') as $color)
            <label>
                <input type="checkbox" name="colors[]" value="{{ $color }}" 
                    {{ in_array($color, request('colors', [])) ? 'checked' : '' }}>
                {{ $color }}
            </label>
        @endforeach
    </div>

    <div class="search_section">
        <h4>価格帯</h4>
        <label>
            <input type="radio" name="price_max" value="" {{ !request('price_max') ? 'checked' : '' }}> 指定なし
        </label>
        @foreach (config('search_options.prices') as $value => $label)
            <label>
                <input type="radio" name="price_max" value="{{ $value }}" 
                    {{ request('price_max') == $value ? 'checked' : '' }}>
                {{ $label }}
            </label>
        @endforeach
    </div>

    <button type="submit">この条件で検索</button>

{{ Form::close() }}
</body>

</html>