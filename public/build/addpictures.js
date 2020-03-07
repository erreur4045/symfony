(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["addpictures"],{

/***/ "./assets/js/addpictures.js":
/*!**********************************!*\
  !*** ./assets/js/addpictures.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var $ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");

var $collectionPictures;
var $addPictureButton = $('<button type="button" class="btn btn-outline-success add_tag_link">Ajouter une image</button>');
var $newLinkPictureLi = $('<li></li>').append($addPictureButton);
$(document).ready(function () {
  // Get the ul that holds the collection of tags
  $collectionPictures = $('ul.images_tricks'); // add the "add a tag" anchor and li to the tags ul

  $collectionPictures.find('li').each(function () {
    addPictureFormDeleteLink($(this));
  });
  $collectionPictures.append($newLinkPictureLi); // count the current form inputs we have (e.g. 2), use that as the new
  // index when inserting a new item (e.g. 2)

  $collectionPictures.data('index', $collectionPictures.find(':input').length);
  $addPictureButton.on('click', function (e) {
    // add a new tag form (see next code block)
    addPictureForm($collectionPictures, $newLinkPictureLi);
  });
});

function addPictureForm($collectionHolder, $newLinkLi) {
  // Get the data-prototype explained earlier
  var prototype = $collectionHolder.data('prototype'); // get the new index

  var index = $collectionHolder.data('index');
  var newForm = prototype; // You need this only if you didn't set 'label' => false in your tags field in TaskType
  // Replace '__name__label__' in the prototype's HTML to
  // instead be a number based on how many items we have
  // newForm = newForm.replace(/__name__label__/g, index);
  // Replace '__name__' in the prototype's HTML to
  // instead be a number based on how many items we have

  newForm = newForm.replace(/__name__/g, index); // increase the index with one for the next item

  $collectionHolder.data('index', index + 1); // Display the form in the page in an li, before the "Add a tag" link li

  var $newFormLi = $('<li></li>').append(newForm);
  $newLinkLi.before($newFormLi);
  addPictureFormDeleteLink($newFormLi);
}

function addPictureFormDeleteLink($tagFormLi) {
  var $removeFormButton = $('<button type="button" class="btn btn-danger" style="margin-bottom: 5px">Supprimer cet élément</button>');
  $tagFormLi.append($removeFormButton);
  $removeFormButton.on('click', function (e) {
    // remove the li for the tag form
    $tagFormLi.remove();
  });
}

/***/ })

},[["./assets/js/addpictures.js","runtime","vendors~addpictures~addvideos~app~carrousel~checkbox~deletecheckbox~loadmorecoms~loadmoretricks"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvYWRkcGljdHVyZXMuanMiXSwibmFtZXMiOlsiJCIsInJlcXVpcmUiLCIkY29sbGVjdGlvblBpY3R1cmVzIiwiJGFkZFBpY3R1cmVCdXR0b24iLCIkbmV3TGlua1BpY3R1cmVMaSIsImFwcGVuZCIsImRvY3VtZW50IiwicmVhZHkiLCJmaW5kIiwiZWFjaCIsImFkZFBpY3R1cmVGb3JtRGVsZXRlTGluayIsImRhdGEiLCJsZW5ndGgiLCJvbiIsImUiLCJhZGRQaWN0dXJlRm9ybSIsIiRjb2xsZWN0aW9uSG9sZGVyIiwiJG5ld0xpbmtMaSIsInByb3RvdHlwZSIsImluZGV4IiwibmV3Rm9ybSIsInJlcGxhY2UiLCIkbmV3Rm9ybUxpIiwiYmVmb3JlIiwiJHRhZ0Zvcm1MaSIsIiRyZW1vdmVGb3JtQnV0dG9uIiwicmVtb3ZlIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7QUFBQSxJQUFNQSxDQUFDLEdBQUdDLG1CQUFPLENBQUMsb0RBQUQsQ0FBakI7O0FBRUEsSUFBSUMsbUJBQUo7QUFDQSxJQUFJQyxpQkFBaUIsR0FBR0gsQ0FBQyxDQUFDLCtGQUFELENBQXpCO0FBQ0EsSUFBSUksaUJBQWlCLEdBQUdKLENBQUMsQ0FBQyxXQUFELENBQUQsQ0FBZUssTUFBZixDQUFzQkYsaUJBQXRCLENBQXhCO0FBRUFILENBQUMsQ0FBQ00sUUFBRCxDQUFELENBQVlDLEtBQVosQ0FBa0IsWUFBVztBQUN6QjtBQUNBTCxxQkFBbUIsR0FBR0YsQ0FBQyxDQUFDLGtCQUFELENBQXZCLENBRnlCLENBR3pCOztBQUNBRSxxQkFBbUIsQ0FBQ00sSUFBcEIsQ0FBeUIsSUFBekIsRUFBK0JDLElBQS9CLENBQW9DLFlBQVc7QUFDM0NDLDRCQUF3QixDQUFDVixDQUFDLENBQUMsSUFBRCxDQUFGLENBQXhCO0FBQ0gsR0FGRDtBQUdBRSxxQkFBbUIsQ0FBQ0csTUFBcEIsQ0FBMkJELGlCQUEzQixFQVB5QixDQVF6QjtBQUNBOztBQUNBRixxQkFBbUIsQ0FBQ1MsSUFBcEIsQ0FBeUIsT0FBekIsRUFBa0NULG1CQUFtQixDQUFDTSxJQUFwQixDQUF5QixRQUF6QixFQUFtQ0ksTUFBckU7QUFFQVQsbUJBQWlCLENBQUNVLEVBQWxCLENBQXFCLE9BQXJCLEVBQThCLFVBQVNDLENBQVQsRUFBWTtBQUN0QztBQUNBQyxrQkFBYyxDQUFDYixtQkFBRCxFQUFzQkUsaUJBQXRCLENBQWQ7QUFDSCxHQUhEO0FBSUgsQ0FoQkQ7O0FBa0JBLFNBQVNXLGNBQVQsQ0FBd0JDLGlCQUF4QixFQUEyQ0MsVUFBM0MsRUFBdUQ7QUFDbkQ7QUFDQSxNQUFJQyxTQUFTLEdBQUdGLGlCQUFpQixDQUFDTCxJQUFsQixDQUF1QixXQUF2QixDQUFoQixDQUZtRCxDQUluRDs7QUFDQSxNQUFJUSxLQUFLLEdBQUdILGlCQUFpQixDQUFDTCxJQUFsQixDQUF1QixPQUF2QixDQUFaO0FBRUEsTUFBSVMsT0FBTyxHQUFHRixTQUFkLENBUG1ELENBUW5EO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTs7QUFDQUUsU0FBTyxHQUFHQSxPQUFPLENBQUNDLE9BQVIsQ0FBZ0IsV0FBaEIsRUFBNkJGLEtBQTdCLENBQVYsQ0FmbUQsQ0FpQm5EOztBQUNBSCxtQkFBaUIsQ0FBQ0wsSUFBbEIsQ0FBdUIsT0FBdkIsRUFBZ0NRLEtBQUssR0FBRyxDQUF4QyxFQWxCbUQsQ0FvQm5EOztBQUNBLE1BQUlHLFVBQVUsR0FBR3RCLENBQUMsQ0FBQyxXQUFELENBQUQsQ0FBZUssTUFBZixDQUFzQmUsT0FBdEIsQ0FBakI7QUFDQUgsWUFBVSxDQUFDTSxNQUFYLENBQWtCRCxVQUFsQjtBQUNBWiwwQkFBd0IsQ0FBQ1ksVUFBRCxDQUF4QjtBQUVIOztBQUNELFNBQVNaLHdCQUFULENBQWtDYyxVQUFsQyxFQUE4QztBQUMxQyxNQUFJQyxpQkFBaUIsR0FBR3pCLENBQUMsQ0FBQyx3R0FBRCxDQUF6QjtBQUNBd0IsWUFBVSxDQUFDbkIsTUFBWCxDQUFrQm9CLGlCQUFsQjtBQUVBQSxtQkFBaUIsQ0FBQ1osRUFBbEIsQ0FBcUIsT0FBckIsRUFBOEIsVUFBU0MsQ0FBVCxFQUFZO0FBQ3RDO0FBQ0FVLGNBQVUsQ0FBQ0UsTUFBWDtBQUNILEdBSEQ7QUFJSCxDIiwiZmlsZSI6ImFkZHBpY3R1cmVzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiY29uc3QgJCA9IHJlcXVpcmUoJ2pxdWVyeScpO1xyXG5cclxubGV0ICRjb2xsZWN0aW9uUGljdHVyZXM7XHJcbmxldCAkYWRkUGljdHVyZUJ1dHRvbiA9ICQoJzxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1vdXRsaW5lLXN1Y2Nlc3MgYWRkX3RhZ19saW5rXCI+QWpvdXRlciB1bmUgaW1hZ2U8L2J1dHRvbj4nKTtcclxubGV0ICRuZXdMaW5rUGljdHVyZUxpID0gJCgnPGxpPjwvbGk+JykuYXBwZW5kKCRhZGRQaWN0dXJlQnV0dG9uKTtcclxuXHJcbiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xyXG4gICAgLy8gR2V0IHRoZSB1bCB0aGF0IGhvbGRzIHRoZSBjb2xsZWN0aW9uIG9mIHRhZ3NcclxuICAgICRjb2xsZWN0aW9uUGljdHVyZXMgPSAkKCd1bC5pbWFnZXNfdHJpY2tzJyk7XHJcbiAgICAvLyBhZGQgdGhlIFwiYWRkIGEgdGFnXCIgYW5jaG9yIGFuZCBsaSB0byB0aGUgdGFncyB1bFxyXG4gICAgJGNvbGxlY3Rpb25QaWN0dXJlcy5maW5kKCdsaScpLmVhY2goZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgYWRkUGljdHVyZUZvcm1EZWxldGVMaW5rKCQodGhpcykpO1xyXG4gICAgfSk7XHJcbiAgICAkY29sbGVjdGlvblBpY3R1cmVzLmFwcGVuZCgkbmV3TGlua1BpY3R1cmVMaSk7XHJcbiAgICAvLyBjb3VudCB0aGUgY3VycmVudCBmb3JtIGlucHV0cyB3ZSBoYXZlIChlLmcuIDIpLCB1c2UgdGhhdCBhcyB0aGUgbmV3XHJcbiAgICAvLyBpbmRleCB3aGVuIGluc2VydGluZyBhIG5ldyBpdGVtIChlLmcuIDIpXHJcbiAgICAkY29sbGVjdGlvblBpY3R1cmVzLmRhdGEoJ2luZGV4JywgJGNvbGxlY3Rpb25QaWN0dXJlcy5maW5kKCc6aW5wdXQnKS5sZW5ndGgpO1xyXG5cclxuICAgICRhZGRQaWN0dXJlQnV0dG9uLm9uKCdjbGljaycsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAvLyBhZGQgYSBuZXcgdGFnIGZvcm0gKHNlZSBuZXh0IGNvZGUgYmxvY2spXHJcbiAgICAgICAgYWRkUGljdHVyZUZvcm0oJGNvbGxlY3Rpb25QaWN0dXJlcywgJG5ld0xpbmtQaWN0dXJlTGkpO1xyXG4gICAgfSk7XHJcbn0pO1xyXG5cclxuZnVuY3Rpb24gYWRkUGljdHVyZUZvcm0oJGNvbGxlY3Rpb25Ib2xkZXIsICRuZXdMaW5rTGkpIHtcclxuICAgIC8vIEdldCB0aGUgZGF0YS1wcm90b3R5cGUgZXhwbGFpbmVkIGVhcmxpZXJcclxuICAgIGxldCBwcm90b3R5cGUgPSAkY29sbGVjdGlvbkhvbGRlci5kYXRhKCdwcm90b3R5cGUnKTtcclxuXHJcbiAgICAvLyBnZXQgdGhlIG5ldyBpbmRleFxyXG4gICAgbGV0IGluZGV4ID0gJGNvbGxlY3Rpb25Ib2xkZXIuZGF0YSgnaW5kZXgnKTtcclxuXHJcbiAgICBsZXQgbmV3Rm9ybSA9IHByb3RvdHlwZTtcclxuICAgIC8vIFlvdSBuZWVkIHRoaXMgb25seSBpZiB5b3UgZGlkbid0IHNldCAnbGFiZWwnID0+IGZhbHNlIGluIHlvdXIgdGFncyBmaWVsZCBpbiBUYXNrVHlwZVxyXG4gICAgLy8gUmVwbGFjZSAnX19uYW1lX19sYWJlbF9fJyBpbiB0aGUgcHJvdG90eXBlJ3MgSFRNTCB0b1xyXG4gICAgLy8gaW5zdGVhZCBiZSBhIG51bWJlciBiYXNlZCBvbiBob3cgbWFueSBpdGVtcyB3ZSBoYXZlXHJcbiAgICAvLyBuZXdGb3JtID0gbmV3Rm9ybS5yZXBsYWNlKC9fX25hbWVfX2xhYmVsX18vZywgaW5kZXgpO1xyXG5cclxuICAgIC8vIFJlcGxhY2UgJ19fbmFtZV9fJyBpbiB0aGUgcHJvdG90eXBlJ3MgSFRNTCB0b1xyXG4gICAgLy8gaW5zdGVhZCBiZSBhIG51bWJlciBiYXNlZCBvbiBob3cgbWFueSBpdGVtcyB3ZSBoYXZlXHJcbiAgICBuZXdGb3JtID0gbmV3Rm9ybS5yZXBsYWNlKC9fX25hbWVfXy9nLCBpbmRleCk7XHJcblxyXG4gICAgLy8gaW5jcmVhc2UgdGhlIGluZGV4IHdpdGggb25lIGZvciB0aGUgbmV4dCBpdGVtXHJcbiAgICAkY29sbGVjdGlvbkhvbGRlci5kYXRhKCdpbmRleCcsIGluZGV4ICsgMSk7XHJcblxyXG4gICAgLy8gRGlzcGxheSB0aGUgZm9ybSBpbiB0aGUgcGFnZSBpbiBhbiBsaSwgYmVmb3JlIHRoZSBcIkFkZCBhIHRhZ1wiIGxpbmsgbGlcclxuICAgIHZhciAkbmV3Rm9ybUxpID0gJCgnPGxpPjwvbGk+JykuYXBwZW5kKG5ld0Zvcm0pO1xyXG4gICAgJG5ld0xpbmtMaS5iZWZvcmUoJG5ld0Zvcm1MaSk7XHJcbiAgICBhZGRQaWN0dXJlRm9ybURlbGV0ZUxpbmsoJG5ld0Zvcm1MaSk7XHJcblxyXG59XHJcbmZ1bmN0aW9uIGFkZFBpY3R1cmVGb3JtRGVsZXRlTGluaygkdGFnRm9ybUxpKSB7XHJcbiAgICB2YXIgJHJlbW92ZUZvcm1CdXR0b24gPSAkKCc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImJ0biBidG4tZGFuZ2VyXCIgc3R5bGU9XCJtYXJnaW4tYm90dG9tOiA1cHhcIj5TdXBwcmltZXIgY2V0IMOpbMOpbWVudDwvYnV0dG9uPicpO1xyXG4gICAgJHRhZ0Zvcm1MaS5hcHBlbmQoJHJlbW92ZUZvcm1CdXR0b24pO1xyXG5cclxuICAgICRyZW1vdmVGb3JtQnV0dG9uLm9uKCdjbGljaycsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAvLyByZW1vdmUgdGhlIGxpIGZvciB0aGUgdGFnIGZvcm1cclxuICAgICAgICAkdGFnRm9ybUxpLnJlbW92ZSgpO1xyXG4gICAgfSk7XHJcbn1cclxuXHJcbiJdLCJzb3VyY2VSb290IjoiIn0=