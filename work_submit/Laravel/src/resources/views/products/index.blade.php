<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>商品一覧</title>
</head>

<body>
    <div class="container-fluid py-4 px-md-5">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3 mb-4">
                @include('products.search')
            </div>
            <div class="col-12 col-md-8 col-lg-9 pl-md-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
                    <h2 class="font-weight-bold text-dark m-0">商品一覧</h2>
                    <span class="text-muted ml-3 small font-weight-bold">
                        {{ $products->total() }}件中 {{ $products->firstItem() }}〜{{ $products->lastItem() }}件を表示
                    </span>
                    <div class="form-inline">
                        <form action="{{ route('products.index') }}" method="GET" class="form-inline">
                            <label for="sort" class="mr-2">並び替え：</label>
                            <select name="sort" id="sort" class="form-control" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>新着順</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>価格の安い順
                                </option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>価格の高い順
                                </option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-12 col-sm-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 product-card">
                                <div class="bg-light position-relative"
                                    style="padding-top: 100%; overflow: hidden; border-radius: 0.25rem 0.25rem 0 0;">
                                    <img src="{{ $product->img_path ? asset('img/' . $product->img_path) : asset('img/no-image.jpg') }}"
                                        class="position-absolute w-100 h-100" style="top: 0; left: 0; object-fit: cover;"
                                        alt="{{ $product->name }}">
                                </div>
                                <div class="card-body d-flex flex-column p-3">
                                    <h6 class="card-title font-weight-bold text-dark mb-2">
                                        {{ $product->name }}
                                    </h6>
                                    <p class="card-text text-danger font-weight-bold h5 mt-auto mb-0">
                                        ¥{{ number_format($product->price) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>