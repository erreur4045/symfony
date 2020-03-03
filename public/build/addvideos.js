(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["addvideos"],{

/***/ "./assets/js/addvideos.js":
/*!********************************!*\
  !*** ./assets/js/addvideos.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {var $collectionHolder1; // setup an "add a tag" link

var $addTagButton1 = $('<button type="button" class="btn btn-outline-success add_tag_link" >Ajouter une video</button>');
var $newLinkLi1 = $('<li></li>').append($addTagButton1);
$(document).ready(function () {
  // Get the ul that holds the collection of tags
  $collectionHolder1 = $('ul.video_tricks');
  $collectionHolder1.find('li').each(function () {
    addTagFormDeleteLink1($(this));
  }); // add the "add a tag" anchor and li to the tags ul

  $collectionHolder1.append($newLinkLi1); // count the current form inputs we have (e.g. 2), use that as the new
  // index when inserting a new item (e.g. 2)

  $collectionHolder1.data('index', $collectionHolder1.find(':input').length);
  $addTagButton1.on('click', function (e) {
    // add a new tag form (see next code block)
    addTagForm1($collectionHolder1, $newLinkLi1);
  });
});

function addTagForm1($collectionHolder1, $newLinkLi1) {
  // Get the data-prototype explained earlier
  var prototype = $collectionHolder1.data('prototype'); // get the new index

  var index = $collectionHolder1.data('index');
  var newForm = prototype; // You need this only if you didn't set 'label' => false in your tags field in TaskType
  // Replace '__name__label__' in the prototype's HTML to
  // instead be a number based on how many items we have
  //newForm = newForm.replace(/__name__label__/g, index);
  // Replace '__name__' in the prototype's HTML to
  // instead be a number based on how many items we have

  newForm = newForm.replace(/__name__/g, index); // increase the index with one for the next item

  $collectionHolder1.data('index', index + 1); // Display the form in the page in an li, before the "Add a tag" link li

  var $newFormLi1 = $('<li></li>').append(newForm);
  $newLinkLi1.before($newFormLi1);
  addTagFormDeleteLink1($newFormLi1);
}

function addTagFormDeleteLink1($tagFormLi) {
  var $removeFormButton = $('<button type="button" class="btn btn-danger" style="margin-bottom: 5px">Supprimer cet élément</button>');
  $tagFormLi.append($removeFormButton);
  $removeFormButton.on('click', function (e) {
    // remove the li for the tag form
    $tagFormLi.remove();
  });
}
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},[["./assets/js/addvideos.js","runtime","vendors~addpictures~addvideos~app~carrousel~checkbox~deletecheckbox~loadmorecoms~loadmoretricks"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvYWRkdmlkZW9zLmpzIl0sIm5hbWVzIjpbIiRjb2xsZWN0aW9uSG9sZGVyMSIsIiRhZGRUYWdCdXR0b24xIiwiJCIsIiRuZXdMaW5rTGkxIiwiYXBwZW5kIiwiZG9jdW1lbnQiLCJyZWFkeSIsImZpbmQiLCJlYWNoIiwiYWRkVGFnRm9ybURlbGV0ZUxpbmsxIiwiZGF0YSIsImxlbmd0aCIsIm9uIiwiZSIsImFkZFRhZ0Zvcm0xIiwicHJvdG90eXBlIiwiaW5kZXgiLCJuZXdGb3JtIiwicmVwbGFjZSIsIiRuZXdGb3JtTGkxIiwiYmVmb3JlIiwiJHRhZ0Zvcm1MaSIsIiRyZW1vdmVGb3JtQnV0dG9uIiwicmVtb3ZlIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7QUFBQSw2Q0FBSUEsa0JBQUosQyxDQUVBOztBQUNBLElBQUlDLGNBQWMsR0FBR0MsQ0FBQyxDQUFDLGdHQUFELENBQXRCO0FBQ0EsSUFBSUMsV0FBVyxHQUFHRCxDQUFDLENBQUMsV0FBRCxDQUFELENBQWVFLE1BQWYsQ0FBc0JILGNBQXRCLENBQWxCO0FBRUFDLENBQUMsQ0FBQ0csUUFBRCxDQUFELENBQVlDLEtBQVosQ0FBa0IsWUFBVztBQUN6QjtBQUNBTixvQkFBa0IsR0FBR0UsQ0FBQyxDQUFDLGlCQUFELENBQXRCO0FBQ0FGLG9CQUFrQixDQUFDTyxJQUFuQixDQUF3QixJQUF4QixFQUE4QkMsSUFBOUIsQ0FBbUMsWUFBVztBQUMxQ0MseUJBQXFCLENBQUNQLENBQUMsQ0FBQyxJQUFELENBQUYsQ0FBckI7QUFDSCxHQUZELEVBSHlCLENBTXpCOztBQUNBRixvQkFBa0IsQ0FBQ0ksTUFBbkIsQ0FBMEJELFdBQTFCLEVBUHlCLENBU3pCO0FBQ0E7O0FBQ0FILG9CQUFrQixDQUFDVSxJQUFuQixDQUF3QixPQUF4QixFQUFpQ1Ysa0JBQWtCLENBQUNPLElBQW5CLENBQXdCLFFBQXhCLEVBQWtDSSxNQUFuRTtBQUVBVixnQkFBYyxDQUFDVyxFQUFmLENBQWtCLE9BQWxCLEVBQTJCLFVBQVNDLENBQVQsRUFBWTtBQUNuQztBQUNBQyxlQUFXLENBQUNkLGtCQUFELEVBQXFCRyxXQUFyQixDQUFYO0FBQ0gsR0FIRDtBQUlILENBakJEOztBQW1CQSxTQUFTVyxXQUFULENBQXFCZCxrQkFBckIsRUFBeUNHLFdBQXpDLEVBQXNEO0FBQ2xEO0FBQ0EsTUFBSVksU0FBUyxHQUFHZixrQkFBa0IsQ0FBQ1UsSUFBbkIsQ0FBd0IsV0FBeEIsQ0FBaEIsQ0FGa0QsQ0FJbEQ7O0FBQ0EsTUFBSU0sS0FBSyxHQUFHaEIsa0JBQWtCLENBQUNVLElBQW5CLENBQXdCLE9BQXhCLENBQVo7QUFFQSxNQUFJTyxPQUFPLEdBQUdGLFNBQWQsQ0FQa0QsQ0FRbEQ7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBOztBQUNBRSxTQUFPLEdBQUdBLE9BQU8sQ0FBQ0MsT0FBUixDQUFnQixXQUFoQixFQUE2QkYsS0FBN0IsQ0FBVixDQWZrRCxDQWlCbEQ7O0FBQ0FoQixvQkFBa0IsQ0FBQ1UsSUFBbkIsQ0FBd0IsT0FBeEIsRUFBaUNNLEtBQUssR0FBRyxDQUF6QyxFQWxCa0QsQ0FvQmxEOztBQUNBLE1BQUlHLFdBQVcsR0FBR2pCLENBQUMsQ0FBQyxXQUFELENBQUQsQ0FBZUUsTUFBZixDQUFzQmEsT0FBdEIsQ0FBbEI7QUFDQWQsYUFBVyxDQUFDaUIsTUFBWixDQUFtQkQsV0FBbkI7QUFDQVYsdUJBQXFCLENBQUNVLFdBQUQsQ0FBckI7QUFDSDs7QUFFRCxTQUFTVixxQkFBVCxDQUErQlksVUFBL0IsRUFBMkM7QUFDdkMsTUFBSUMsaUJBQWlCLEdBQUdwQixDQUFDLENBQUMsd0dBQUQsQ0FBekI7QUFDQW1CLFlBQVUsQ0FBQ2pCLE1BQVgsQ0FBa0JrQixpQkFBbEI7QUFFQUEsbUJBQWlCLENBQUNWLEVBQWxCLENBQXFCLE9BQXJCLEVBQThCLFVBQVNDLENBQVQsRUFBWTtBQUN0QztBQUNBUSxjQUFVLENBQUNFLE1BQVg7QUFDSCxHQUhEO0FBSUgsQyIsImZpbGUiOiJhZGR2aWRlb3MuanMiLCJzb3VyY2VzQ29udGVudCI6WyJsZXQgJGNvbGxlY3Rpb25Ib2xkZXIxO1xyXG5cclxuLy8gc2V0dXAgYW4gXCJhZGQgYSB0YWdcIiBsaW5rXHJcbmxldCAkYWRkVGFnQnV0dG9uMSA9ICQoJzxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1vdXRsaW5lLXN1Y2Nlc3MgYWRkX3RhZ19saW5rXCIgPkFqb3V0ZXIgdW5lIHZpZGVvPC9idXR0b24+Jyk7XHJcbmxldCAkbmV3TGlua0xpMSA9ICQoJzxsaT48L2xpPicpLmFwcGVuZCgkYWRkVGFnQnV0dG9uMSk7XHJcblxyXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpIHtcclxuICAgIC8vIEdldCB0aGUgdWwgdGhhdCBob2xkcyB0aGUgY29sbGVjdGlvbiBvZiB0YWdzXHJcbiAgICAkY29sbGVjdGlvbkhvbGRlcjEgPSAkKCd1bC52aWRlb190cmlja3MnKTtcclxuICAgICRjb2xsZWN0aW9uSG9sZGVyMS5maW5kKCdsaScpLmVhY2goZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgYWRkVGFnRm9ybURlbGV0ZUxpbmsxKCQodGhpcykpO1xyXG4gICAgfSk7XHJcbiAgICAvLyBhZGQgdGhlIFwiYWRkIGEgdGFnXCIgYW5jaG9yIGFuZCBsaSB0byB0aGUgdGFncyB1bFxyXG4gICAgJGNvbGxlY3Rpb25Ib2xkZXIxLmFwcGVuZCgkbmV3TGlua0xpMSk7XHJcblxyXG4gICAgLy8gY291bnQgdGhlIGN1cnJlbnQgZm9ybSBpbnB1dHMgd2UgaGF2ZSAoZS5nLiAyKSwgdXNlIHRoYXQgYXMgdGhlIG5ld1xyXG4gICAgLy8gaW5kZXggd2hlbiBpbnNlcnRpbmcgYSBuZXcgaXRlbSAoZS5nLiAyKVxyXG4gICAgJGNvbGxlY3Rpb25Ib2xkZXIxLmRhdGEoJ2luZGV4JywgJGNvbGxlY3Rpb25Ib2xkZXIxLmZpbmQoJzppbnB1dCcpLmxlbmd0aCk7XHJcblxyXG4gICAgJGFkZFRhZ0J1dHRvbjEub24oJ2NsaWNrJywgZnVuY3Rpb24oZSkge1xyXG4gICAgICAgIC8vIGFkZCBhIG5ldyB0YWcgZm9ybSAoc2VlIG5leHQgY29kZSBibG9jaylcclxuICAgICAgICBhZGRUYWdGb3JtMSgkY29sbGVjdGlvbkhvbGRlcjEsICRuZXdMaW5rTGkxKTtcclxuICAgIH0pO1xyXG59KTtcclxuXHJcbmZ1bmN0aW9uIGFkZFRhZ0Zvcm0xKCRjb2xsZWN0aW9uSG9sZGVyMSwgJG5ld0xpbmtMaTEpIHtcclxuICAgIC8vIEdldCB0aGUgZGF0YS1wcm90b3R5cGUgZXhwbGFpbmVkIGVhcmxpZXJcclxuICAgIGxldCBwcm90b3R5cGUgPSAkY29sbGVjdGlvbkhvbGRlcjEuZGF0YSgncHJvdG90eXBlJyk7XHJcblxyXG4gICAgLy8gZ2V0IHRoZSBuZXcgaW5kZXhcclxuICAgIGxldCBpbmRleCA9ICRjb2xsZWN0aW9uSG9sZGVyMS5kYXRhKCdpbmRleCcpO1xyXG5cclxuICAgIGxldCBuZXdGb3JtID0gcHJvdG90eXBlO1xyXG4gICAgLy8gWW91IG5lZWQgdGhpcyBvbmx5IGlmIHlvdSBkaWRuJ3Qgc2V0ICdsYWJlbCcgPT4gZmFsc2UgaW4geW91ciB0YWdzIGZpZWxkIGluIFRhc2tUeXBlXHJcbiAgICAvLyBSZXBsYWNlICdfX25hbWVfX2xhYmVsX18nIGluIHRoZSBwcm90b3R5cGUncyBIVE1MIHRvXHJcbiAgICAvLyBpbnN0ZWFkIGJlIGEgbnVtYmVyIGJhc2VkIG9uIGhvdyBtYW55IGl0ZW1zIHdlIGhhdmVcclxuICAgIC8vbmV3Rm9ybSA9IG5ld0Zvcm0ucmVwbGFjZSgvX19uYW1lX19sYWJlbF9fL2csIGluZGV4KTtcclxuXHJcbiAgICAvLyBSZXBsYWNlICdfX25hbWVfXycgaW4gdGhlIHByb3RvdHlwZSdzIEhUTUwgdG9cclxuICAgIC8vIGluc3RlYWQgYmUgYSBudW1iZXIgYmFzZWQgb24gaG93IG1hbnkgaXRlbXMgd2UgaGF2ZVxyXG4gICAgbmV3Rm9ybSA9IG5ld0Zvcm0ucmVwbGFjZSgvX19uYW1lX18vZywgaW5kZXgpO1xyXG5cclxuICAgIC8vIGluY3JlYXNlIHRoZSBpbmRleCB3aXRoIG9uZSBmb3IgdGhlIG5leHQgaXRlbVxyXG4gICAgJGNvbGxlY3Rpb25Ib2xkZXIxLmRhdGEoJ2luZGV4JywgaW5kZXggKyAxKTtcclxuXHJcbiAgICAvLyBEaXNwbGF5IHRoZSBmb3JtIGluIHRoZSBwYWdlIGluIGFuIGxpLCBiZWZvcmUgdGhlIFwiQWRkIGEgdGFnXCIgbGluayBsaVxyXG4gICAgdmFyICRuZXdGb3JtTGkxID0gJCgnPGxpPjwvbGk+JykuYXBwZW5kKG5ld0Zvcm0pO1xyXG4gICAgJG5ld0xpbmtMaTEuYmVmb3JlKCRuZXdGb3JtTGkxKTtcclxuICAgIGFkZFRhZ0Zvcm1EZWxldGVMaW5rMSgkbmV3Rm9ybUxpMSk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGFkZFRhZ0Zvcm1EZWxldGVMaW5rMSgkdGFnRm9ybUxpKSB7XHJcbiAgICB2YXIgJHJlbW92ZUZvcm1CdXR0b24gPSAkKCc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImJ0biBidG4tZGFuZ2VyXCIgc3R5bGU9XCJtYXJnaW4tYm90dG9tOiA1cHhcIj5TdXBwcmltZXIgY2V0IMOpbMOpbWVudDwvYnV0dG9uPicpO1xyXG4gICAgJHRhZ0Zvcm1MaS5hcHBlbmQoJHJlbW92ZUZvcm1CdXR0b24pO1xyXG5cclxuICAgICRyZW1vdmVGb3JtQnV0dG9uLm9uKCdjbGljaycsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAvLyByZW1vdmUgdGhlIGxpIGZvciB0aGUgdGFnIGZvcm1cclxuICAgICAgICAkdGFnRm9ybUxpLnJlbW92ZSgpO1xyXG4gICAgfSk7XHJcbn0iXSwic291cmNlUm9vdCI6IiJ9