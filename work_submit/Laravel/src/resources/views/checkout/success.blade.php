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
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 text-center">

                {{-- 成功を祝うカードコンテナ --}}
                <div class="card shadow border-0 py-5 px-4 bg-white rounded-4">
                    <div class="card-body">

                        {{-- グリーンのでっかいチェックアイコン --}}
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle"
                                style="width: 90px; height: 90px;">
                                <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                            </div>
                        </div>

                        {{-- メッセージエリア --}}
                        <h2 class="fw-bold text-dark mb-3">ご注文ありがとうございます！</h2>
                        <p class="text-secondary mb-4 lead">
                            決済が正常に完了し、注文が確定いたしました。<br>
                            商品の発送準備が整い次第、メールにてご連絡いたします。
                        </p>

                        {{-- 丁寧な区切り線 --}}
                        <hr class="my-4 text-muted opacity-25">

                        {{-- 次のアクションへの誘導ボタン --}}
                        <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                            {{-- トップページへ戻る（主要ボタン） --}}
                            <a href="{{ url('/') }}" class="btn btn-primary btn-lg px-4 fw-bold shadow-sm">
                                <i class="bi bi-house-door me-2"></i>トップページへ
                            </a>
                        </div>

                    </div>
                </div>

                {{-- フッター代わりのサポート文言（任意） --}}
                <p class="mt-4 text-muted small">
                    ご不明な点がございましたら、お気軽に <a href="#" class="text-decoration-none">お問い合わせ</a> までご連絡ください。
                </p>

            </div>
        </div>
    </div>
</body>

</html>