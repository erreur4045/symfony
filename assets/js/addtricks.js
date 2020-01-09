const $ = require('jquery');
$(function () {
    $(document).delegate('.checkbox_check', 'change', function (e) {
        let isChecked = $(this).is(':checked');
        if (isChecked){
            let checkboxes = $('.checkbox_check');
            console.log(checkboxes);
            let currentCheckbox = $(this).data('id');
            console.log(currentCheckbox);
            checkboxes.each(function (index, elt) {
                if ($(elt).data('id') !== currentCheckbox) {
                   $(elt).prop('checked', false);
               }
            });
        }
    })
});