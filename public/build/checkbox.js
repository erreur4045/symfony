(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["checkbox"],{

/***/ "./assets/js/checkbox.js":
/*!*******************************!*\
  !*** ./assets/js/checkbox.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$(function () {
  $(document).delegate('.checkbox_check', 'change', function (e) {
    var isChecked = $(this).is(':checked');

    if (isChecked) {
      var checkboxes = $('.checkbox_check');
      var currentCheckbox = $(this).data('id');
      checkboxes.each(function (index, elt) {
        if ($(elt).data('id') !== currentCheckbox) {
          $(elt).prop('checked', false);
        }
      });
    }
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},[["./assets/js/checkbox.js","runtime","vendors~addpictures~addvideos~app~carrousel~checkbox~deletecheckbox~loadmorecoms~loadmoretricks"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvY2hlY2tib3guanMiXSwibmFtZXMiOlsiJCIsImRvY3VtZW50IiwiZGVsZWdhdGUiLCJlIiwiaXNDaGVja2VkIiwiaXMiLCJjaGVja2JveGVzIiwiY3VycmVudENoZWNrYm94IiwiZGF0YSIsImVhY2giLCJpbmRleCIsImVsdCIsInByb3AiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7OztBQUFBQSwwQ0FBQyxDQUFDLFlBQVk7QUFDVkEsR0FBQyxDQUFDQyxRQUFELENBQUQsQ0FBWUMsUUFBWixDQUFxQixpQkFBckIsRUFBd0MsUUFBeEMsRUFBa0QsVUFBVUMsQ0FBVixFQUFhO0FBQzNELFFBQUlDLFNBQVMsR0FBR0osQ0FBQyxDQUFDLElBQUQsQ0FBRCxDQUFRSyxFQUFSLENBQVcsVUFBWCxDQUFoQjs7QUFDQSxRQUFJRCxTQUFKLEVBQWM7QUFDVixVQUFJRSxVQUFVLEdBQUdOLENBQUMsQ0FBQyxpQkFBRCxDQUFsQjtBQUNBLFVBQUlPLGVBQWUsR0FBR1AsQ0FBQyxDQUFDLElBQUQsQ0FBRCxDQUFRUSxJQUFSLENBQWEsSUFBYixDQUF0QjtBQUNBRixnQkFBVSxDQUFDRyxJQUFYLENBQWdCLFVBQVVDLEtBQVYsRUFBaUJDLEdBQWpCLEVBQXNCO0FBQ2xDLFlBQUlYLENBQUMsQ0FBQ1csR0FBRCxDQUFELENBQU9ILElBQVAsQ0FBWSxJQUFaLE1BQXNCRCxlQUExQixFQUEyQztBQUN2Q1AsV0FBQyxDQUFDVyxHQUFELENBQUQsQ0FBT0MsSUFBUCxDQUFZLFNBQVosRUFBdUIsS0FBdkI7QUFDSDtBQUNKLE9BSkQ7QUFLSDtBQUNKLEdBWEQ7QUFZSCxDQWJBLENBQUQsQyIsImZpbGUiOiJjaGVja2JveC5qcyIsInNvdXJjZXNDb250ZW50IjpbIiQoZnVuY3Rpb24gKCkge1xyXG4gICAgJChkb2N1bWVudCkuZGVsZWdhdGUoJy5jaGVja2JveF9jaGVjaycsICdjaGFuZ2UnLCBmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgIGxldCBpc0NoZWNrZWQgPSAkKHRoaXMpLmlzKCc6Y2hlY2tlZCcpO1xyXG4gICAgICAgIGlmIChpc0NoZWNrZWQpe1xyXG4gICAgICAgICAgICBsZXQgY2hlY2tib3hlcyA9ICQoJy5jaGVja2JveF9jaGVjaycpO1xyXG4gICAgICAgICAgICBsZXQgY3VycmVudENoZWNrYm94ID0gJCh0aGlzKS5kYXRhKCdpZCcpO1xyXG4gICAgICAgICAgICBjaGVja2JveGVzLmVhY2goZnVuY3Rpb24gKGluZGV4LCBlbHQpIHtcclxuICAgICAgICAgICAgICAgIGlmICgkKGVsdCkuZGF0YSgnaWQnKSAhPT0gY3VycmVudENoZWNrYm94KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgJChlbHQpLnByb3AoJ2NoZWNrZWQnLCBmYWxzZSk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH1cclxuICAgIH0pXHJcbn0pOyJdLCJzb3VyY2VSb290IjoiIn0=