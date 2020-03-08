/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');
jQuery.noConflict();
require('bootstrap');

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

$(function () {
    $(document).delegate('.custom-file-input', 'change', function () {
        let inputFile = $(event.currentTarget);
        let labelToShow = $(inputFile[0].activeElement.labels[1]);
        $(inputFile)
            .find(labelToShow)
            .html(inputFile[0].activeElement.files[0].name);
    });
});