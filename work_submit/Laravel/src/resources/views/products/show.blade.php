<body>

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $product->name }}</title>
    </head>

    <body>
        <div class="container py-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 p-0" style="list-style: none;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('products.index') }}">商品一覧</a>
                    </li>
                    @if(isset($category))
                        <li class="breadcrumb-item">
                            <a href="{{ route('products.index', ['category_id' => $category->id]) }}"
                                class="text-decoration-none text-secondary">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active text-dark" aria-current="page">
                        {{ $product->name }}
                    </li>
                </ol>
            </nav>

            <div class="row">
                <div class="product-images mx-auto" style="max-width: 400px;">

                    {{-- 1. メイン画像表示エリア --}}
                    <div class="main-image-container mb-3 shadow-sm rounded position-relative mx-auto"
                        style="width: 100%; max-width: 400px;">
                        @php
                            // 💡 まず、その商品自身の画像（img_url や img_path）を最優先でチェックします
                            $mainImg = $product->img_url ?? $product->img_path ?? null;

                            // もし商品自身の画像が空で、imgsテーブルにデータがあるなら1枚目を採用
                            if (!$mainImg && isset($product->imgs) && $product->imgs->isNotEmpty()) {
                                $mainImg = $product->imgs->first()->img_url ?? $product->imgs->first()->image_path ?? null;
                            }

                            // 💡 取得した画像名から、正しいURLを組み立てる
                            if ($mainImg) {
                                $mainImg = preg_replace('#^/#', '', $mainImg);
                                if (preg_match('#^https?://#', $mainImg)) {
                                    $mainSrc = $mainImg;
                                } elseif (strpos($mainImg, 'img/') === 0 || strpos($mainImg, 'storage/') === 0) {
                                    $mainSrc = asset($mainImg);
                                } else {
                                    $mainSrc = asset('storage/products/' . $mainImg);
                                }
                            } else {
                                // 商品画像が本当に何もないときだけ、最終手段としてNO IMAGE画像を出す
                                $mainSrc = asset('img/products/m_shirt_main.jpeg');
                            }
                        @endphp

                        <div class="ratio ratio-1x1">
                            {{-- 💡 src に上で判定した $mainSrc を指定します --}}
                            <img id="mainTarget" src="{{ $mainSrc }}" alt="{{ $product->name }}"
                                class="w-100 h-100 rounded object-fit-cover"
                                onerror="this.src='{{ asset('img/products/m_shirt_main.jpeg') }}';">
                        </div>

                        {{-- 画像が2枚以上（複数）あるときだけ、左右の矢印ボタンを表示する --}}
                        @if(isset($product->imgs) && $product->imgs->count() > 1)
                            <button type="button"
                                class="btn btn-dark btn-sm position-absolute top-50 start-0 translate-middle-y ms-2 opacity-75"
                                onclick="moveSlider(-1)"
                                style="z-index: 10; width: 32px; height: 32px; padding: 0; border-radius: 50%;">
                                ＜
                            </button>

                            <button type="button"
                                class="btn btn-dark btn-sm position-absolute top-50 end-0 translate-middle-y me-2 opacity-75"
                                onclick="moveSlider(1)"
                                style="z-index: 10; width: 32px; height: 32px; padding: 0; border-radius: 50%;">
                                ＞
                            </button>
                        @endif
                    </div>

                    {{-- 2. サブ画像エリア（2枚以上あるときだけ表示） --}}
                    @if(isset($product->imgs) && $product->imgs->count() > 1)
                        <div class="sub-images-container d-flex gap-2 flex-wrap">
                            @foreach($product->imgs as $image)
                                @php
                                    $currentImg = $image->img_url ?? $image->image_path ?? null;
                                    if ($currentImg) {
                                        $currentImg = preg_replace('#^/#', '', $currentImg);
                                        if (preg_match('#^https?://#', $currentImg)) {
                                            $subSrc = $currentImg;
                                        } elseif (strpos($currentImg, 'img/') === 0 || strpos($currentImg, 'storage/') === 0) {
                                            $subSrc = asset($currentImg);
                                        } else {
                                            $subSrc = asset('storage/products/' . $currentImg);
                                        }
                                    } else {
                                        $subSrc = asset('img/products/m_shirt_main.jpeg');
                                    }
                                @endphp
                                <div class="thumbnail-box" style="width: 60px; height: 60px;">
                                    <img src="{{ $subSrc }}" alt="商品画像" data-index="{{ $loop->index }}"
                                        class="product-thumbnail img-thumbnail w-100 h-100 object-fit-cover @if($loop->first) border-dark border-3 @endif"
                                        style="cursor: pointer;" onclick="changeMainImage({{ $loop->index }})"
                                        onerror="this.src='{{ asset('img/products/m_shirt_main.jpeg') }}';">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-12 col-md-6">
                    <h1 class="h2 font-weight-bold text-dark mb-3">{{ $product->name }}</h1>
                    <p class="text-danger h3 font-weight-bold mb-4">
                        ¥{{ number_format($product->price) }}
                    </p>
                    <!--ログイン状態だとお気に入り機能表示-->
                    @auth
                        <button type="button"
                            class="favorite-btn btn {{ $product->productFavorites->where('user_id', auth()->id())->count() ? 'btn-primary is-favorited' : 'btn-outline-primary' }}"
                            data-store-url="{{ route('favorite.store', $product->id) }}"
                            data-delete-url="{{ route('favorite.destroy', $product->id) }}">
                            <i
                                class="fa-heart {{ $product->productFavorites->where('user_id', auth()->id())->count() ? 'fas' : 'far' }}"></i>
                            {{ $product->productFavorites->where('user_id', auth()->id())->count() ? 'お気に入り済み' : 'お気に入りに追加' }}
                        </button>
                    @endauth
                    <form method="post" action="{{ route('cart.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_variation_id" id="productVariationId" value="">
                        <div class="product-selection-area mt-3" style="max-width: 500px;">
                            {{-- 1. 在庫状況表示バッジ --}}
                            <div class="d-flex align-items-center mb-3">
                                <span class="font-weight-bold me-2 text-secondary" style="font-size: 1rem;">在庫状況:</span>
                                <span id="stockLabel"
                                    class="badge bg-light text-dark border px-3 py-2 d-inline-flex align-items-center"
                                    style="font-size: 0.95rem; border-radius: 6px;">
                                    <span class="me-2" style="color: #6c757d;">●</span> カラーとサイズを選択
                                </span>
                            </div>

                            {{-- 🎨 2. カラー選択（name="color_id" にして直接IDを送信） --}}
                            <div class="mb-3 d-flex align-items-center">
                                <label for="colorSelect" class="font-weight-bold text-secondary mb-0 me-3"
                                    style="width: 70px; flex-shrink: 0;">カラー:</label>
                                <select name="color_id" id="colorSelect"
                                    class="form-control custom-select border-secondary-subtle py-2 px-3"
                                    style="height: auto; border-radius: 6px; font-size: 0.95rem;" required>
                                    <option value="">選択してください</option>
                                    @foreach($allColors as $color)
                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 📏 3. サイズ選択（name="size_id" にして直接IDを送信） --}}
                            <div class="mb-4 d-flex align-items-center">
                                <label for="sizeSelect" class="font-weight-bold text-secondary mb-0 me-3"
                                    style="width: 70px; flex-shrink: 0;">サイズ:</label>
                                <select name="size_id" id="sizeSelect"
                                    class="form-control custom-select border-secondary-subtle py-2 px-3"
                                    style="height: auto; border-radius: 6px; font-size: 0.95rem;" required>
                                    <option value="">選択してください</option>
                                    @foreach($allSizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 🔢 4. 数量選択（name="quantity" がカートの数量に直結します） --}}
                            <div class="mb-4 d-flex align-items-center">
                                <label for="quantitySelect" class="font-weight-bold mb-0 me-3"
                                    style="width: 70px; flex-shrink: 0;">数量:</label>
                                <select name="quantity" id="quantitySelect"
                                    class="form-control custom-select border-secondary-subtle py-2 px-3"
                                    style="max-width: 90px; height: auto; border-radius: 6px; font-size: 0.95rem;">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                            @if ($errors->any())
                                <div style="color: red; background: #f8d7da; padding: 10px; margin-bottom: 10px;">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            {{-- 6. カートボタン（type="submit" に変更してフォームを送信できるようにします） --}}
                            <div class="d-flex gap-3 align-items-center">
                                <button type="submit"
                                    class="btn btn-primary fw-bold flex-grow-1 py-3 d-flex align-items-center justify-content-center gap-2"
                                    id="addToCartBtn"
                                    style="font-size: 1.1rem; border-radius: 6px; background-color: #2b75bc; border: none;"
                                    disabled>
                                    カートに入れる
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                let currentImageIndex = 0;

                // メイン画像表示
                function changeMainImage(index) {
                    const mainImg = document.getElementById('mainTarget');
                    const thumbnails = document.querySelectorAll('.product-thumbnail');

                    if (index < 0 || index >= thumbnails.length) return;
                    currentImageIndex = index;

                    // メイン画像を更新
                    const selectedThumb = thumbnails[index];
                    mainImg.src = selectedThumb.src;

                    // サムネイルの枠線を更新
                    thumbnails.forEach(t => {
                        t.classList.remove('border-3', 'border-dark');
                        t.style.borderWidth = '1px';
                    });
                    selectedThumb.classList.add('border-dark', 'border-3');
                    selectedThumb.style.borderWidth = '3px';
                }

                // 左右ボタンでスライド
                function moveSlider(direction) {
                    const thumbnails = document.querySelectorAll('.product-thumbnail');
                    let newIndex = currentImageIndex + direction;

                    // ループ処理
                    if (newIndex < 0) {
                        newIndex = thumbnails.length - 1;
                    } else if (newIndex >= thumbnails.length) {
                        newIndex = 0;
                    }

                    changeMainImage(newIndex);
                }

                // 在庫ステータス処理
                const MyVariants = @json($product->variants);
                document.querySelectorAll('#colorSelect, #sizeSelect').forEach(element => {
                    element.addEventListener('change', updateStockDisplay);
                });

                function updateStockDisplay() {
                    const colorSelect = document.getElementById('colorSelect');
                    const sizeSelect = document.getElementById('sizeSelect');
                    const quantitySelect = document.getElementById('quantitySelect');
                    const stockLabel = document.getElementById('stockLabel');
                    const addToCartBtn = document.getElementById('addToCartBtn');
                    const color = parseInt(colorSelect.value || 0, 10);
                    const size = parseInt(sizeSelect.value || 0, 10);
                    const selectedQuantity = parseInt(quantitySelect.value, 10) || 1;

                    if (!color || !size) {
                        stockLabel.className = "badge bg-light text-dark border px-3 py-2";
                        stockLabel.innerHTML = "<span style='color: #6c757d;' class='me-2'>●</span> カラーとサイズを選択";
                        document.getElementById('productVariationId').value = '';
                        setCartButtonState(addToCartBtn, true);
                        return;
                    }

                    const matchedVariant = MyVariants.find(v => {
                        const vColorId = v.color_id ?? (v.color && v.color.id) ?? null;
                        const vSizeId = v.size_id ?? (v.size && v.size.id) ?? null;
                        return parseInt(vColorId, 10) === color && parseInt(vSizeId, 10) === size;
                    });

                    if (!matchedVariant) {
                        stockLabel.className = "badge bg-danger text-white px-3 py-2";
                        stockLabel.innerHTML = "<span class='me-2'>●</span> 売り切れ";
                        document.getElementById('productVariationId').value = '';
                        setCartButtonState(addToCartBtn, true);
                        return;
                    }
                    document.getElementById('productVariationId').value = matchedVariant.id;
                    const stock = Number(matchedVariant.stock || 0);
                    if (selectedQuantity > stock) {
                        stockLabel.className = "badge bg-danger text-white px-3 py-2";
                        stockLabel.innerHTML = `<span class='me-2'>●</span> 選択数量が在庫数を超えています（最大 ${stock} 点）`;
                        setCartButtonState(addToCartBtn, true);
                        return;
                    }

                    if (stock >= 10) {
                        stockLabel.className = "badge bg-success text-white px-3 py-2";
                        stockLabel.innerHTML = "<span class='me-2'>●</span> 在庫あり";
                    } else if (stock > 0) {
                        stockLabel.className = "badge bg-warning text-dark px-3 py-2";
                        stockLabel.innerHTML = `<span class='me-2'>●</span> 残りわずか（あと ${stock} 点）`;
                    } else {
                        stockLabel.className = "badge bg-danger text-white px-3 py-2";
                        stockLabel.innerHTML = "<span class='me-2'>●</span> 売り切れ";
                        setCartButtonState(addToCartBtn, true);
                        return;
                    }

                    setCartButtonState(addToCartBtn, false);
                }

                function setCartButtonState(button, disabled) {
                    button.disabled = disabled;
                    button.style.backgroundColor = disabled ? '#6c757d' : '#2b75bc';
                    button.style.cursor = disabled ? 'not-allowed' : 'pointer';
                    button.style.opacity = disabled ? '0.65' : '1';
                }

                document.getElementById('quantitySelect').addEventListener('change', updateStockDisplay);


                let isProcessing = false;

                document.querySelectorAll('.favorite-btn').forEach(el => {
                    el.addEventListener('click', async () => {
                        if (isProcessing) return;
                        isProcessing = true;

                        const storeUrl = el.dataset.storeUrl;
                        const deleteUrl = el.dataset.deleteUrl;
                        const csrf = document.querySelector('meta[name="csrf-token"]').content;
                        const isFavorited = el.classList.contains('is-favorited');

                        try {
                            const response = await fetch(isFavorited ? deleteUrl : storeUrl, {
                                method: isFavorited ? 'DELETE' : 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf
                                }
                            });

                            if (!response.ok) {
                                throw new Error('HTTP error ' + response.status);
                            }

                            const result = await response.json();

                            // 👇 ここで元々のコードと新しいクラス切り替えのコードを綺麗にまとめています
                            if (result.status === 'added') {
                                el.classList.add('is-favorited', 'btn-primary');     // お気に入り状態と色付きクラスを追加
                                el.classList.remove('btn-outline-primary');          // 色抜きクラスを削除
                                el.innerHTML = '<i class="fas fa-heart"></i> お気に入り済み';
                            } else if (result.status === 'removed') {
                                el.classList.remove('is-favorited', 'btn-primary');  // お気に入り状態と色付きクラスを削除
                                el.classList.add('btn-outline-primary');             // 色抜きクラスを追加
                                el.innerHTML = '<i class="far fa-heart"></i> お気に入りに追加';
                            }

                        } catch (error) {
                            console.error('favorite error:', error);
                            alert('通信エラーが発生しました');
                        } finally {
                            isProcessing = false;
                        }
                    });
                });
            </script>
    </body>

    </html>