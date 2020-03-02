(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["loadmoretricks"],{

/***/ "./assets/js/loadmoretricks.js":
/*!*************************************!*\
  !*** ./assets/js/loadmoretricks.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$('.btn_load_tricks').on('click', function () {
  $('#loader').css('background', 'transparent');
  $('#loader').css('visibility', 'visible');
  $('#tricks_list').css('pointer-events', 'none');
  $('#btn_load_tricks').css('pointer-events', 'none');
  var page = $(this).data('page');
  var pagemax = $(this).data('pagemax');
  var loadMore = $('#btn_load_tricks');
  var url = loadMore.data('url') + '?page=' + page;
  var tricks = $('#tricks_list');
  $.ajax({
    method: 'GET',
    url: url,
    success: function success(response) {
      tricks.append(response);
      loadMore.data('page', page + 1);
      document.getElementById("loader").style.visibility = "hidden";
      $('#tricks_list').css('pointer-events', 'all');
      $('#btn_load_tricks').css('pointer-events', 'all');

      if (page >= pagemax) {
        $('.btn_load_tricks').remove();
      }
    }
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},[["./assets/js/loadmoretricks.js","runtime","vendors~addpictures~addvideos~app~carrousel~checkbox~deletecheckbox~loadmorecoms~loadmoretricks"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbG9hZG1vcmV0cmlja3MuanMiXSwibmFtZXMiOlsiJCIsIm9uIiwiY3NzIiwicGFnZSIsImRhdGEiLCJwYWdlbWF4IiwibG9hZE1vcmUiLCJ1cmwiLCJ0cmlja3MiLCJhamF4IiwibWV0aG9kIiwic3VjY2VzcyIsInJlc3BvbnNlIiwiYXBwZW5kIiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsInN0eWxlIiwidmlzaWJpbGl0eSIsInJlbW92ZSJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7O0FBQUFBLDBDQUFDLENBQUMsa0JBQUQsQ0FBRCxDQUFzQkMsRUFBdEIsQ0FBeUIsT0FBekIsRUFBa0MsWUFBWTtBQUMxQ0QsR0FBQyxDQUFDLFNBQUQsQ0FBRCxDQUFhRSxHQUFiLENBQWlCLFlBQWpCLEVBQStCLGFBQS9CO0FBQ0FGLEdBQUMsQ0FBQyxTQUFELENBQUQsQ0FBYUUsR0FBYixDQUFpQixZQUFqQixFQUErQixTQUEvQjtBQUNBRixHQUFDLENBQUMsY0FBRCxDQUFELENBQWtCRSxHQUFsQixDQUFzQixnQkFBdEIsRUFBd0MsTUFBeEM7QUFDQUYsR0FBQyxDQUFDLGtCQUFELENBQUQsQ0FBc0JFLEdBQXRCLENBQTBCLGdCQUExQixFQUE0QyxNQUE1QztBQUNBLE1BQUlDLElBQUksR0FBR0gsQ0FBQyxDQUFDLElBQUQsQ0FBRCxDQUFRSSxJQUFSLENBQWEsTUFBYixDQUFYO0FBQ0EsTUFBSUMsT0FBTyxHQUFHTCxDQUFDLENBQUMsSUFBRCxDQUFELENBQVFJLElBQVIsQ0FBYSxTQUFiLENBQWQ7QUFDQSxNQUFJRSxRQUFRLEdBQUdOLENBQUMsQ0FBQyxrQkFBRCxDQUFoQjtBQUNBLE1BQUlPLEdBQUcsR0FBR0QsUUFBUSxDQUFDRixJQUFULENBQWMsS0FBZCxJQUFxQixRQUFyQixHQUErQkQsSUFBekM7QUFDQSxNQUFJSyxNQUFNLEdBQUdSLENBQUMsQ0FBQyxjQUFELENBQWQ7QUFDQUEsR0FBQyxDQUFDUyxJQUFGLENBQU87QUFDSEMsVUFBTSxFQUFFLEtBREw7QUFFSEgsT0FBRyxFQUFFQSxHQUZGO0FBR0hJLFdBQU8sRUFBRSxpQkFBVUMsUUFBVixFQUFvQjtBQUN6QkosWUFBTSxDQUFDSyxNQUFQLENBQWNELFFBQWQ7QUFDQU4sY0FBUSxDQUFDRixJQUFULENBQWMsTUFBZCxFQUFzQkQsSUFBSSxHQUFHLENBQTdCO0FBQ0FXLGNBQVEsQ0FBQ0MsY0FBVCxDQUF3QixRQUF4QixFQUFrQ0MsS0FBbEMsQ0FBd0NDLFVBQXhDLEdBQXFELFFBQXJEO0FBQ0FqQixPQUFDLENBQUMsY0FBRCxDQUFELENBQWtCRSxHQUFsQixDQUFzQixnQkFBdEIsRUFBd0MsS0FBeEM7QUFDQUYsT0FBQyxDQUFDLGtCQUFELENBQUQsQ0FBc0JFLEdBQXRCLENBQTBCLGdCQUExQixFQUE0QyxLQUE1Qzs7QUFDQSxVQUFJQyxJQUFJLElBQUlFLE9BQVosRUFBb0I7QUFDaEJMLFNBQUMsQ0FBQyxrQkFBRCxDQUFELENBQXNCa0IsTUFBdEI7QUFDSDtBQUNKO0FBWkUsR0FBUDtBQWNILENBeEJELEUiLCJmaWxlIjoibG9hZG1vcmV0cmlja3MuanMiLCJzb3VyY2VzQ29udGVudCI6WyIkKCcuYnRuX2xvYWRfdHJpY2tzJykub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xyXG4gICAgJCgnI2xvYWRlcicpLmNzcygnYmFja2dyb3VuZCcsICd0cmFuc3BhcmVudCcpO1xyXG4gICAgJCgnI2xvYWRlcicpLmNzcygndmlzaWJpbGl0eScsICd2aXNpYmxlJyk7XHJcbiAgICAkKCcjdHJpY2tzX2xpc3QnKS5jc3MoJ3BvaW50ZXItZXZlbnRzJywgJ25vbmUnKTtcclxuICAgICQoJyNidG5fbG9hZF90cmlja3MnKS5jc3MoJ3BvaW50ZXItZXZlbnRzJywgJ25vbmUnKTtcclxuICAgIGxldCBwYWdlID0gJCh0aGlzKS5kYXRhKCdwYWdlJyk7XHJcbiAgICBsZXQgcGFnZW1heCA9ICQodGhpcykuZGF0YSgncGFnZW1heCcpO1xyXG4gICAgbGV0IGxvYWRNb3JlID0gJCgnI2J0bl9sb2FkX3RyaWNrcycpO1xyXG4gICAgbGV0IHVybCA9IGxvYWRNb3JlLmRhdGEoJ3VybCcpKyc/cGFnZT0nKyBwYWdlO1xyXG4gICAgbGV0IHRyaWNrcyA9ICQoJyN0cmlja3NfbGlzdCcpO1xyXG4gICAgJC5hamF4KHtcclxuICAgICAgICBtZXRob2Q6ICdHRVQnLFxyXG4gICAgICAgIHVybDogdXJsLFxyXG4gICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChyZXNwb25zZSkge1xyXG4gICAgICAgICAgICB0cmlja3MuYXBwZW5kKHJlc3BvbnNlKTtcclxuICAgICAgICAgICAgbG9hZE1vcmUuZGF0YSgncGFnZScsIHBhZ2UgKyAxICk7XHJcbiAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwibG9hZGVyXCIpLnN0eWxlLnZpc2liaWxpdHkgPSBcImhpZGRlblwiO1xyXG4gICAgICAgICAgICAkKCcjdHJpY2tzX2xpc3QnKS5jc3MoJ3BvaW50ZXItZXZlbnRzJywgJ2FsbCcpO1xyXG4gICAgICAgICAgICAkKCcjYnRuX2xvYWRfdHJpY2tzJykuY3NzKCdwb2ludGVyLWV2ZW50cycsICdhbGwnKTtcclxuICAgICAgICAgICAgaWYgKHBhZ2UgPj0gcGFnZW1heCl7XHJcbiAgICAgICAgICAgICAgICAkKCcuYnRuX2xvYWRfdHJpY2tzJykucmVtb3ZlKCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9KTtcclxufSk7Il0sInNvdXJjZVJvb3QiOiIifQ==