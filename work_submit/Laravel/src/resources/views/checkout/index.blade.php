<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>カート一覧画面</title>
</head>

<body>
    <div class="container py-5">
        <div class="row g-5">

            {{-- 左側カラム：お届け先と商品リスト --}}
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-4 text-dark border-bottom pb-2 fw-bold">ご購入手続き</h4>

                {{-- 1. お届け先情報の入力フォーム --}}
                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                    @csrf
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light fw-bold">
                            <i class="bi bi-truck me-2"></i>お届け先住所
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="name" class="form-label fw-semibold text-secondary small">お名前</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="山田 太郎"
                                        required>
                                </div>

                                <div class="col-sm-4">
                                    <label for="postal_code"
                                        class="form-label fw-semibold text-secondary small">郵便番号</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code"
                                        placeholder="123-4567" required>
                                </div>

                                <div class="col-12">
                                    <label for="address1"
                                        class="form-label fw-semibold text-secondary small text-nowrap">住所（都道府県・市区町村・番地）</label>
                                    <input type="text" class="form-control" id="address1" name="address1"
                                        placeholder="東京都渋谷区宇田川町1-1" required>
                                </div>

                                <div class="col-12">
                                    <label for="building"
                                        class="form-label fw-semibold text-secondary small">建物名・部屋番号（任意）</label>
                                    <input type="text" class="form-control" id="building" name="building"
                                        placeholder="〇〇マンション 101号室">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- 2. カート内の商品確認リスト --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        <i class="bi bi-cart shadow-sm-check me-2"></i>注文内容の確認
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light small text-uppercase text-secondary">
                                    <tr>
                                        <th scope="col" class="ps-4">商品名</th>
                                        <th scope="col" class="text-center">数量</th>
                                        <th scope="col" class="text-end pe-4">小計</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach ($cart->cartItems as $item)
                                        @php
                                            $product = $item->productVariation->product;
                                            $subtotal = $product->price * $item->quantity;
                                            $total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">{{ $product->name }}</div>
                                                @if($item->productVariation->name)
                                                    <small class="text-muted">バリエーション:
                                                        {{ $item->productVariation->name }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center fw-semibold text-secondary">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-dark">
                                                ¥{{ number_format($subtotal) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 右側カラム：サイドバー（請求サマリー） --}}
            <div class="col-md-5 col-lg-4">
                <div class="position-sticky" style="top: 2rem;">
                    <h4 class="d-flex justify-content-between align-items-center mb-3 text-dark fw-bold">
                        <span>注文明細</span>
                        <span class="badge bg-secondary rounded-pill">{{ $cart->cartItems->sum('quantity') }}</span>
                    </h4>

                    <div class="card shadow-sm mb-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="text-secondary small">商品合計</div>
                                <span class="fw-semibold text-dark">¥{{ number_format($total) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="text-secondary small">配送料</div>
                                <span class="text-success fw-semibold">無料</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 bg-light">
                                <strong class="text-dark">お支払い合計</strong>
                                <strong class="text-danger fs-5">¥{{ number_format($total) }}</strong>
                            </li>
                        </ul>

                        <div class="card-body">
                            {{-- 💡 外側にあるFormをボタン単体で送信させるテクニック (form属性を使用) --}}
                            <button type="submit" form="checkout-form"
                                class="w-100 btn btn-primary btn-lg fw-bold py-3 shadow-sm">
                                <i class="bi bi-credit-card me-2"></i>Stripe決済へ進む
                            </button>
                            <p class="text-muted text-center small mt-3 mb-0">
                                ※次の画面でクレジットカード情報を安全に入力していただけます。
                            </p>
                        </div>
                    </div>

                    {{-- カートに戻るリンク --}}
                    <div class="text-center">
                        <a href="{{ route('cart.index') }}"
                            class="btn btn-link text-decoration-none text-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>カートに戻って変更する
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('checkout-form').addEventListener('submit', function () {
            var btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Stripe決済へ移動中...';
        });
    </script>
</body>

</html>