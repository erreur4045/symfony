$('.btn_load_tricks').on('click', function () {
    $('#loader').css('background', 'transparent');
    $('#loader').css('visibility', 'visible');
    $('#tricks_list').css('pointer-events', 'none');
    $('#btn_load_tricks').css('pointer-events', 'none');
    let page = $(this).data('page');
    let pagemax = $(this).data('pagemax');
    let loadMore = $('#btn_load_tricks');
    let url = loadMore.data('url')+'?page='+ page;
    let tricks = $('#tricks_list');
    $.ajax({
        method: 'GET',
        url: url,
        success: function (response) {
            tricks.append(response);
            loadMore.data('page', page + 1 );
            document.getElementById("loader").style.visibility = "hidden";
            $('#tricks_list').css('pointer-events', 'all');
            $('#btn_load_tricks').css('pointer-events', 'all');
            if (page >= pagemax){
                $('.btn_load_tricks').remove();
            }
        }
    });
});