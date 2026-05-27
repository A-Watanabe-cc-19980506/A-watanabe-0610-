<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>top画面</title>
</head>

<body>
<div class="search_condition">
    <ul>
        @if(!empty(request()->keyword))
        <li class="search_condition_item">
            {{request()->keyword }}<a href="keyword" class="search_condition_a"><i class="fas fa-times search_condition_delete"></i></a>
        </li>
        @endif

        @if(!empty(request()->genre))
        <li class="search_condition_item">
            {{request()->genre }}<a href="genre" class="search_condition_a"><i class="fas fa-times search_condition_delete"></i></a>
        </li>
        @endif
    </ul>
</div>

</body>

</html>