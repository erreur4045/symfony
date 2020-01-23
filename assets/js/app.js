/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');
jQuery.noConflict();
require('bootstrap');

//console.log($.fn.jquery)
function openModal(slug,message)
{
    $('#exampleModal').modal('show');
    $('#exampleModal .modal-body').text(message);
    $('#exampleModal .btn_delete_modal').off('click').on('click', function() {
        location.href = slug;
    });
}

$('.delete_modal').on('click', function () {
    let slug = $(this).data('slug');
    let message = $(this).data('message');
    openModal(slug,message);
});

$('.btn_load_tricks').on('click', function () {
    $('#loader').css('background', 'transparent');
    $('#loader').css('visibility', 'visible');
    $('#tricks_list').css('pointer-events', 'none');
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
           if (page >= pagemax){
               $('.btn_load_tricks').remove();
           }
       }
   });
});

$('.btn_load_coms').on('click', function () {
    $('#loader').css('background', 'transparent');
    $('#loader').css('visibility', 'visible');
    $('#coms_list').css('pointer-events', 'none');
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
            loadMore.data('page', page + 1 );
            document.getElementById("loader").style.visibility = "hidden";
            $('#coms_list').css('pointer-events', 'all');
            if (page >= pagemax){
                $('.btn_load_coms').remove();
            }
        }
    });
});

$('#btn_see_media').on('click',function () {
    document.getElementById("carousel_mobile").style.display = "block";
    document.getElementById("btn_see_media").style.display = "none";
});

$(document).scroll(function() {
    var y = $(this).scrollTop();
    if (y > 300) {
        $('.bottom-buttom').fadeIn();
    } else {
        $('.bottom-buttom').fadeOut();
    }
});