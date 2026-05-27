$(function () {
    $('.search_condition_a').each(function () {
       //href属性を取得
        attr = $(this).attr('href');
       //初期化
        var url = new URL(window.location);
       //パラメーター削除
        url.searchParams.delete(attr);
        if (url.search) {
            $(this).attr('href', 'search' +url.search);
        } else {
            $(this).attr('href', 'search');
        }
    });
});