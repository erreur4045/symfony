const $ = require('jquery');
$(function () {
    $('.tinymce').on('change', function (e) {
        console.log($(this));
    })
});