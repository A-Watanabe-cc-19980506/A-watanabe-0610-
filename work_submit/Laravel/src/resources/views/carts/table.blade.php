<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>カート一覧画面</title>
</head>

<body>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($cartItems->count())
        <form id="checkoutForm" action="{{ route('cart.checkout.check') }}" method="POST">
                @csrf
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>✓</th>
                            <th>商品名</th>
                            <th>単価</th>
                            <th>個数</th>
                            <th>-/+</th>
                            <th>小計</th>
                            <th>削除</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalPrice = 0; @endphp
                        @foreach($cartItems as $item)
                            @php
                                $price = $item->productVariation->product->price;
                                $itemSubtotal = $price * $item->quantity;
                                $totalPrice += $itemSubtotal;
                                $stock = $item->productVariation->stock ?? 0;
                            @endphp

                            <tr>
                                <td>
                                    <input type="checkbox" name="cart_item_ids[]" value="{{ $item->id }}" class="cart-checkbox">
                                </td>
                                <td>
                                    {{ $item->productVariation->product->name }}<br>
                                    {{ $item->productVariation->color->name }},
                                    {{ $item->productVariation->size->name }}
                                </td>
                                <td>
                                    {{ number_format($item->productVariation->product->price) }}円
                                </td>
                                <td>
                                    <span class="mx-3 font-weight-bold" id="quantity-{{ $item->id }}"
                                        style="font-size: 1.1rem; min-width: 30px; text-align: center;">
                                        {{ $item->quantity }}
                                        <br>
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div
                                        class="d-inline-flex align-items-center justify-content-center bg-light rounded border p-1 shadow-sm">
                                        <button type="button"
                                            class="btn btn-sm btn-light border-0 fw-bold btn-quantity-minus d-flex align-items-center justify-content-center"
                                            data-id="{{ $item->id }}"
                                            style="width: 32px; height: 32px; font-size: 1.1rem; border-radius: 4px; background: transparent;">
                                            －
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm btn-light border-0 fw-bold btn-quantity-plus d-flex align-items-center justify-content-center"
                                            data-id="{{ $item->id }}"
                                            style="width: 32px; height: 32px; font-size: 1.1rem; border-radius: 4px; background: transparent;">
                                            ＋
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <span id="subtotal-{{ $item->id }}" class="item-subtotal" data-price="{{ $price }}">
                                        {{ number_format($itemSubtotal) }}
                                    </span>円
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" type="submit"
                                        form="delete-form-{{ $item->id }}"
                                        onclick="return confirm('本当に削除しますか？')">
                                        削除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        <div class="text-end mt-4">
                            <h4>合計金額: <span id="total-price" class="text-danger">{{ number_format($totalPrice) }}</span>円
                            </h4>
                        </div>
                    </tbody>
                </table>
                <div class="d-flex gap-3 align-items-center">
                    <button type="submit"
                        class="btn btn-primary fw-bold flex-grow-1 py-3 d-flex align-items-center justify-content-center gap-2"
                        id="addToCartBtn"
                        style="font-size: 1.1rem; border-radius: 6px; background-color: #2b75bc; border: none;" disabled>
                        購入に進む
                    </button>
                </div>
            </form>
            @foreach($cartItems as $item)
                <form id="delete-form-{{ $item->id }}" action="{{ route('cart.destroy', $item->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
            <a href="{{ route('products.index') }}">商品一覧検索へ</a>
    @else
            <p>カートに商品はありません。</p>
            <a href="{{ route('products.index') }}">商品一覧検索へ</a>
        @endif
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const checkoutBtn = document.getElementById('addToCartBtn');

                // 🧮 【ルール】✓が入っている行の小計だけを合計する関数
                function calculateTotal() {
                    let total = 0;
                    let anyChecked = false;

                    // 画面内のすべての「小計（.item-subtotal）」をループでチェック
                    document.querySelectorAll('.item-subtotal').forEach(subtotalSpan => {
                        const row = subtotalSpan.closest('tr');
                        const checkbox = row.querySelector('.cart-checkbox');

                        // 💡 ルール：チェックボックスに✓が入っているときだけ合計に足す！
                        if (checkbox && checkbox.checked) {
                            anyChecked = true;
                            // 表示されている「14,940」などの文字からカンマを除去して数値にする
                            const subtotalText = subtotalSpan.textContent.replace(/,/g, '');
                            total += parseInt(subtotalText, 10) || 0;
                        }
                    });

                    // 総合計の表示を書き換える
                    const totalPriceSpan = document.getElementById('total-price');
                    if (totalPriceSpan) {
                        totalPriceSpan.textContent = total.toLocaleString(); // 3桁カンマ区切り
                    }

                    // 🛒 ボタンの有効・無効化制御（✓が1つもなければ押せない）
                    if (checkoutBtn) {
                        if (anyChecked) {
                            checkoutBtn.removeAttribute('disabled');
                            checkoutBtn.style.opacity = '1';
                        } else {
                            checkoutBtn.setAttribute('disabled', 'true');
                            checkoutBtn.style.opacity = '0.5';
                        }
                    }
                }

                // 🗳️ チェックボックス（✓）がクリックされたらタイムリーに合計を再計算
                document.querySelectorAll('.cart-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', () => {
                        calculateTotal();
                    });
                });

                // ➖ マイナスボタンのクリック
                document.querySelectorAll('.btn-quantity-minus').forEach(button => {
                    button.addEventListener('click', async () => {
                        const id = button.dataset.id;
                        const qtySpan = document.getElementById(`quantity-${id}`);
                        if (!qtySpan) return;

                        let currentQty = parseInt(qtySpan.textContent, 10) || 1;
                        if (currentQty > 1) {

                            const newQty = currentQty - 1;
                            await updateCartQuantity(id, newQty, qtySpan);
                        }
                    });
                });

                // ➕ プラスボタンのクリック
                document.querySelectorAll('.btn-quantity-plus').forEach(button => {
                    button.addEventListener('click', async () => {
                        const id = button.dataset.id;
                        const qtySpan = document.getElementById(`quantity-${id}`);
                        if (!qtySpan) return;

                        let currentQty = parseInt(qtySpan.textContent, 10) || 1;
                        const newQty = currentQty + 1;
                        await updateCartQuantity(id, newQty, qtySpan);
                    });
                });

                // 🔄 サーバー送信 ＆ タイムリーな小計・合計の変化
                async function updateCartQuantity(id, newQty, qtySpan) {
                    if (!csrfToken) return;
                    try {
                        const response = await fetch(`/cart/${id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ quantity: newQty })
                        });

                        if (!response.ok) throw new Error('通信失敗');

                        const result = await response.json();
                        if (result.status === 'success') {
                            // ① 数量の文字をその場で書き換え
                            qtySpan.textContent = result.new_quantity;

                            // ② この行の「小計」をタイムリーに再計算して書き換え
                            const subtotalSpan = document.getElementById(`subtotal-${id}`);
                            if (subtotalSpan) {
                                const price = parseInt(subtotalSpan.dataset.price, 10) || 0;
                                // 単価 × 新しい数量 を計算してカンマ区切りで表示
                                subtotalSpan.textContent = (price * result.new_quantity).toLocaleString();
                            }

                            // ③ 全体の合計金額を再計算（✓ルール適用）
                            calculateTotal();
                        }
                    } catch (error) {
                        console.error(error);
                    }
                }

                // 最初の画面表示時にもルールを適用して計算しておく
                calculateTotal();
            });
        </script>

</body>

</html>