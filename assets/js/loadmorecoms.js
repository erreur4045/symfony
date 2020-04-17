$('.btn_load_coms').on('click', function () {
    $('#loader').css('background', 'transparent');
    $('#loader').css('visibility', 'visible');
    $('#coms_list').css('pointer-events', 'none');
    $('#btn_load_coms').css('pointer-events', 'none');
    let page = $(this).data('page');
    let pagemax = $(this).data('pagemax');
    let figureId = $(this).data('figureid');
    let loadMore = $('#btn_load_coms');
    let url = loadMore.data('url')+'?page='+ page+'&figureid='+figureId;
    let coms = $('#coms_list');
    $.ajax({
        method: 'GET',
        url: url,
        success: function (response) {
            coms.append(response);
            loadMore.data('page', page + 1);
            document.getElementById("loader").style.visibility = "hidden";
            $('#coms_list').css('pointer-events', 'all');
            $('#btn_load_coms').css('pointer-events', 'all');
            if (page >= pagemax) {
                $('.btn_load_coms').remove();
            }
        }
    });
});