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
let $collectionHolder;

// setup an "add a tag" link
let $addTagButton = $('<button type="button" class="btn btn-outline-success add_tag_link">Ajouter une image</button>');
let $newLinkLi = $('<li></li>').append($addTagButton);

$(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.images_tricks');
    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
    });
});

function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    let prototype = $collectionHolder.data('prototype');

    // get the new index
    let index = $collectionHolder.data('index');

    let newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}
//todo : du sale !
let $collectionHolder1;

// setup an "add a tag" link
let $addTagButton1 = $('<button type="button" class="btn btn-outline-success add_tag_link">Ajouter une video</button>');
let $newLinkLi1 = $('<li></li>').append($addTagButton1);

$(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder1 = $('ul.video_tricks');
    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder1.append($newLinkLi1);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder1.data('index', $collectionHolder1.find(':input').length);

    $addTagButton1.on('click', function(e) {
        // add a new tag form (see next code block)
        addTagForm1($collectionHolder1, $newLinkLi1);
    });
});

function addTagForm1($collectionHolder1, $newLinkLi1) {
    // Get the data-prototype explained earlier
    let prototype = $collectionHolder1.data('prototype');

    // get the new index
    let index = $collectionHolder1.data('index');

    let newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder1.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi1 = $('<li></li>').append(newForm);
    $newLinkLi1.before($newFormLi1);
}

/*

$('.custom-file-input').on('change', function(event) {
    var inputFile = event.currentTarget;
    $(inputFile).parent()
        .find('.custom-file-label')
        .html(inputFile.files[0].name);
});
*/
