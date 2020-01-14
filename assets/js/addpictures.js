const $ = require('jquery');

let $collectionPictures;
// todo : ajouter dans le champs formulaire le nom de la photo.
// setup an "add a tag" link
let $addPictureButton = $('<button type="button" class="btn btn-outline-success add_tag_link">Ajouter une image</button>');
let $newLinkPictureLi = $('<li></li>').append($addPictureButton);

$(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionPictures = $('ul.images_tricks');
    // add the "add a tag" anchor and li to the tags ul
    $collectionPictures.find('li').each(function() {
        addPictureFormDeleteLink($(this));
    });
    $collectionPictures.append($newLinkPictureLi);
    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionPictures.data('index', $collectionPictures.find(':input').length);

    $addPictureButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addPictureForm($collectionPictures, $newLinkPictureLi);
    });
});

function addPictureForm($collectionHolder, $newLinkLi) {
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
    addPictureFormDeleteLink($newFormLi);

}
function addPictureFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="btn btn-danger" style="margin-bottom: 5px">Supprimer cet élément</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}