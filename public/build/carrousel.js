(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["carrousel"],{

/***/ "./assets/js/carrousel.js":
/*!********************************!*\
  !*** ./assets/js/carrousel.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {__webpack_require__(/*! core-js/modules/es.array.find */ "./node_modules/core-js/modules/es.array.find.js");

__webpack_require__(/*! core-js/modules/es.parse-int */ "./node_modules/core-js/modules/es.parse-int.js");

__webpack_require__(/*! core-js/modules/es.regexp.exec */ "./node_modules/core-js/modules/es.regexp.exec.js");

__webpack_require__(/*! core-js/modules/es.string.match */ "./node_modules/core-js/modules/es.string.match.js");

__webpack_require__(/*! core-js/modules/es.string.split */ "./node_modules/core-js/modules/es.string.split.js");

$(document).ready(function () {
  var itemsMainDiv = '.MultiCarousel';
  var itemsDiv = '.MultiCarousel-inner';
  var itemWidth = "";
  $('.leftLst, .rightLst').click(function () {
    var condition = $(this).hasClass("leftLst");
    if (condition) click(0, this);else click(1, this);
  });
  ResCarouselSize();
  $(window).resize(function () {
    ResCarouselSize();
  }); //this function define the size of the items

  function ResCarouselSize() {
    var incno = 0;
    var dataItems = "data-items";
    var itemClass = '.item';
    var id = 0;
    var btnParentSb = '';
    var itemsSplit = '';
    var sampwidth = $(itemsMainDiv).width();
    var bodyWidth = $('body').width();
    $(itemsDiv).each(function () {
      id = id + 1;
      var itemNumbers = $(this).find(itemClass).length;
      btnParentSb = $(this).parent().attr(dataItems);
      itemsSplit = btnParentSb.split(',');
      $(this).parent().attr("id", "MultiCarousel" + id);

      if (bodyWidth >= 1200) {
        incno = itemsSplit[3];
        itemWidth = sampwidth / incno;
      } else if (bodyWidth >= 992) {
        incno = itemsSplit[2];
        itemWidth = sampwidth / incno;
      } else if (bodyWidth >= 768) {
        incno = itemsSplit[1];
        itemWidth = sampwidth / incno;
      } else {
        incno = itemsSplit[0];
        itemWidth = sampwidth / incno;

        if (itemWidth <= 0) {
          itemWidth = bodyWidth - 2;
        }
      }

      $(this).css({
        'transform': 'translateX(0px)',
        'width': itemWidth * itemNumbers
      });
      $(this).find(itemClass).each(function () {
        $(this).outerWidth(itemWidth);
      });
      $(".leftLst").addClass("over");
      $(".rightLst").removeClass("over");
    });
  } //this function used to move the items


  function ResCarousel(e, el, s) {
    var leftBtn = '.leftLst';
    var rightBtn = '.rightLst';
    var translateXval = '';
    var divStyle = $(el + ' ' + itemsDiv).css('transform');
    var values = divStyle.match(/-?[\d\.]+/g);
    var xds = Math.abs(values[4]);

    if (e === 0) {
      translateXval = parseInt(xds) - parseInt(itemWidth * s);
      $(el + ' ' + rightBtn).removeClass("over");

      if (translateXval <= itemWidth / 2) {
        translateXval = 0;
        $(el + ' ' + leftBtn).addClass("over");
      }
    } else if (e === 1) {
      var itemsCondition = $(el).find(itemsDiv).width() - $(el).width();
      translateXval = parseInt(xds) + parseInt(itemWidth * s);
      $(el + ' ' + leftBtn).removeClass("over");

      if (translateXval >= itemsCondition - itemWidth / 2) {
        translateXval = itemsCondition;
        $(el + ' ' + rightBtn).addClass("over");
      }
    }

    $(el + ' ' + itemsDiv).css('transform', 'translateX(' + -translateXval + 'px)');
  } //It is used to get some elements from btn


  function click(ell, ee) {
    var Parent = "#" + $(ee).parent().attr("id");
    var slide = $(Parent).attr("data-slide");
    ResCarousel(ell, Parent, slide);
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},[["./assets/js/carrousel.js","runtime","vendors~addpictures~addvideos~app~carrousel~checkbox~loadmorecoms~loadmoretricks","vendors~addpictures~addvideos~carrousel","vendors~carrousel"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvY2Fycm91c2VsLmpzIl0sIm5hbWVzIjpbIiQiLCJkb2N1bWVudCIsInJlYWR5IiwiaXRlbXNNYWluRGl2IiwiaXRlbXNEaXYiLCJpdGVtV2lkdGgiLCJjbGljayIsImNvbmRpdGlvbiIsImhhc0NsYXNzIiwiUmVzQ2Fyb3VzZWxTaXplIiwid2luZG93IiwicmVzaXplIiwiaW5jbm8iLCJkYXRhSXRlbXMiLCJpdGVtQ2xhc3MiLCJpZCIsImJ0blBhcmVudFNiIiwiaXRlbXNTcGxpdCIsInNhbXB3aWR0aCIsIndpZHRoIiwiYm9keVdpZHRoIiwiZWFjaCIsIml0ZW1OdW1iZXJzIiwiZmluZCIsImxlbmd0aCIsInBhcmVudCIsImF0dHIiLCJzcGxpdCIsImNzcyIsIm91dGVyV2lkdGgiLCJhZGRDbGFzcyIsInJlbW92ZUNsYXNzIiwiUmVzQ2Fyb3VzZWwiLCJlIiwiZWwiLCJzIiwibGVmdEJ0biIsInJpZ2h0QnRuIiwidHJhbnNsYXRlWHZhbCIsImRpdlN0eWxlIiwidmFsdWVzIiwibWF0Y2giLCJ4ZHMiLCJNYXRoIiwiYWJzIiwicGFyc2VJbnQiLCJpdGVtc0NvbmRpdGlvbiIsImVsbCIsImVlIiwiUGFyZW50Iiwic2xpZGUiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUFBQUEsQ0FBQyxDQUFDQyxRQUFELENBQUQsQ0FBWUMsS0FBWixDQUFrQixZQUFZO0FBQzFCLE1BQUlDLFlBQVksR0FBSSxnQkFBcEI7QUFDQSxNQUFJQyxRQUFRLEdBQUksc0JBQWhCO0FBQ0EsTUFBSUMsU0FBUyxHQUFHLEVBQWhCO0FBRUFMLEdBQUMsQ0FBQyxxQkFBRCxDQUFELENBQXlCTSxLQUF6QixDQUErQixZQUFZO0FBQ3ZDLFFBQUlDLFNBQVMsR0FBR1AsQ0FBQyxDQUFDLElBQUQsQ0FBRCxDQUFRUSxRQUFSLENBQWlCLFNBQWpCLENBQWhCO0FBQ0EsUUFBSUQsU0FBSixFQUNJRCxLQUFLLENBQUMsQ0FBRCxFQUFJLElBQUosQ0FBTCxDQURKLEtBR0lBLEtBQUssQ0FBQyxDQUFELEVBQUksSUFBSixDQUFMO0FBQ1AsR0FORDtBQVFBRyxpQkFBZTtBQUtmVCxHQUFDLENBQUNVLE1BQUQsQ0FBRCxDQUFVQyxNQUFWLENBQWlCLFlBQVk7QUFDekJGLG1CQUFlO0FBQ2xCLEdBRkQsRUFsQjBCLENBc0IxQjs7QUFDQSxXQUFTQSxlQUFULEdBQTJCO0FBQ3ZCLFFBQUlHLEtBQUssR0FBRyxDQUFaO0FBQ0EsUUFBSUMsU0FBUyxHQUFJLFlBQWpCO0FBQ0EsUUFBSUMsU0FBUyxHQUFJLE9BQWpCO0FBQ0EsUUFBSUMsRUFBRSxHQUFHLENBQVQ7QUFDQSxRQUFJQyxXQUFXLEdBQUcsRUFBbEI7QUFDQSxRQUFJQyxVQUFVLEdBQUcsRUFBakI7QUFDQSxRQUFJQyxTQUFTLEdBQUdsQixDQUFDLENBQUNHLFlBQUQsQ0FBRCxDQUFnQmdCLEtBQWhCLEVBQWhCO0FBQ0EsUUFBSUMsU0FBUyxHQUFHcEIsQ0FBQyxDQUFDLE1BQUQsQ0FBRCxDQUFVbUIsS0FBVixFQUFoQjtBQUNBbkIsS0FBQyxDQUFDSSxRQUFELENBQUQsQ0FBWWlCLElBQVosQ0FBaUIsWUFBWTtBQUN6Qk4sUUFBRSxHQUFHQSxFQUFFLEdBQUcsQ0FBVjtBQUNBLFVBQUlPLFdBQVcsR0FBR3RCLENBQUMsQ0FBQyxJQUFELENBQUQsQ0FBUXVCLElBQVIsQ0FBYVQsU0FBYixFQUF3QlUsTUFBMUM7QUFDQVIsaUJBQVcsR0FBR2hCLENBQUMsQ0FBQyxJQUFELENBQUQsQ0FBUXlCLE1BQVIsR0FBaUJDLElBQWpCLENBQXNCYixTQUF0QixDQUFkO0FBQ0FJLGdCQUFVLEdBQUdELFdBQVcsQ0FBQ1csS0FBWixDQUFrQixHQUFsQixDQUFiO0FBQ0EzQixPQUFDLENBQUMsSUFBRCxDQUFELENBQVF5QixNQUFSLEdBQWlCQyxJQUFqQixDQUFzQixJQUF0QixFQUE0QixrQkFBa0JYLEVBQTlDOztBQUdBLFVBQUlLLFNBQVMsSUFBSSxJQUFqQixFQUF1QjtBQUNuQlIsYUFBSyxHQUFHSyxVQUFVLENBQUMsQ0FBRCxDQUFsQjtBQUNBWixpQkFBUyxHQUFHYSxTQUFTLEdBQUdOLEtBQXhCO0FBQ0gsT0FIRCxNQUlLLElBQUlRLFNBQVMsSUFBSSxHQUFqQixFQUFzQjtBQUN2QlIsYUFBSyxHQUFHSyxVQUFVLENBQUMsQ0FBRCxDQUFsQjtBQUNBWixpQkFBUyxHQUFHYSxTQUFTLEdBQUdOLEtBQXhCO0FBQ0gsT0FISSxNQUlBLElBQUlRLFNBQVMsSUFBSSxHQUFqQixFQUFzQjtBQUN2QlIsYUFBSyxHQUFHSyxVQUFVLENBQUMsQ0FBRCxDQUFsQjtBQUNBWixpQkFBUyxHQUFHYSxTQUFTLEdBQUdOLEtBQXhCO0FBQ0gsT0FISSxNQUlBO0FBQ0RBLGFBQUssR0FBR0ssVUFBVSxDQUFDLENBQUQsQ0FBbEI7QUFDQVosaUJBQVMsR0FBR2EsU0FBUyxHQUFHTixLQUF4Qjs7QUFDQSxZQUFHUCxTQUFTLElBQUksQ0FBaEIsRUFBa0I7QUFDZEEsbUJBQVMsR0FBR2UsU0FBUyxHQUFHLENBQXhCO0FBQ0g7QUFDSjs7QUFDRHBCLE9BQUMsQ0FBQyxJQUFELENBQUQsQ0FBUTRCLEdBQVIsQ0FBWTtBQUFFLHFCQUFhLGlCQUFmO0FBQWtDLGlCQUFTdkIsU0FBUyxHQUFHaUI7QUFBdkQsT0FBWjtBQUNBdEIsT0FBQyxDQUFDLElBQUQsQ0FBRCxDQUFRdUIsSUFBUixDQUFhVCxTQUFiLEVBQXdCTyxJQUF4QixDQUE2QixZQUFZO0FBQ3JDckIsU0FBQyxDQUFDLElBQUQsQ0FBRCxDQUFRNkIsVUFBUixDQUFtQnhCLFNBQW5CO0FBQ0gsT0FGRDtBQUlBTCxPQUFDLENBQUMsVUFBRCxDQUFELENBQWM4QixRQUFkLENBQXVCLE1BQXZCO0FBQ0E5QixPQUFDLENBQUMsV0FBRCxDQUFELENBQWUrQixXQUFmLENBQTJCLE1BQTNCO0FBRUgsS0FuQ0Q7QUFvQ0gsR0FwRXlCLENBdUUxQjs7O0FBQ0EsV0FBU0MsV0FBVCxDQUFxQkMsQ0FBckIsRUFBd0JDLEVBQXhCLEVBQTRCQyxDQUE1QixFQUErQjtBQUMzQixRQUFJQyxPQUFPLEdBQUksVUFBZjtBQUNBLFFBQUlDLFFBQVEsR0FBSSxXQUFoQjtBQUNBLFFBQUlDLGFBQWEsR0FBRyxFQUFwQjtBQUNBLFFBQUlDLFFBQVEsR0FBR3ZDLENBQUMsQ0FBQ2tDLEVBQUUsR0FBRyxHQUFMLEdBQVc5QixRQUFaLENBQUQsQ0FBdUJ3QixHQUF2QixDQUEyQixXQUEzQixDQUFmO0FBQ0EsUUFBSVksTUFBTSxHQUFHRCxRQUFRLENBQUNFLEtBQVQsQ0FBZSxZQUFmLENBQWI7QUFDQSxRQUFJQyxHQUFHLEdBQUdDLElBQUksQ0FBQ0MsR0FBTCxDQUFTSixNQUFNLENBQUMsQ0FBRCxDQUFmLENBQVY7O0FBQ0EsUUFBSVAsQ0FBQyxLQUFLLENBQVYsRUFBYTtBQUNUSyxtQkFBYSxHQUFHTyxRQUFRLENBQUNILEdBQUQsQ0FBUixHQUFnQkcsUUFBUSxDQUFDeEMsU0FBUyxHQUFHOEIsQ0FBYixDQUF4QztBQUNBbkMsT0FBQyxDQUFDa0MsRUFBRSxHQUFHLEdBQUwsR0FBV0csUUFBWixDQUFELENBQXVCTixXQUF2QixDQUFtQyxNQUFuQzs7QUFFQSxVQUFJTyxhQUFhLElBQUlqQyxTQUFTLEdBQUcsQ0FBakMsRUFBb0M7QUFDaENpQyxxQkFBYSxHQUFHLENBQWhCO0FBQ0F0QyxTQUFDLENBQUNrQyxFQUFFLEdBQUcsR0FBTCxHQUFXRSxPQUFaLENBQUQsQ0FBc0JOLFFBQXRCLENBQStCLE1BQS9CO0FBQ0g7QUFDSixLQVJELE1BU0ssSUFBSUcsQ0FBQyxLQUFLLENBQVYsRUFBYTtBQUNkLFVBQUlhLGNBQWMsR0FBRzlDLENBQUMsQ0FBQ2tDLEVBQUQsQ0FBRCxDQUFNWCxJQUFOLENBQVduQixRQUFYLEVBQXFCZSxLQUFyQixLQUErQm5CLENBQUMsQ0FBQ2tDLEVBQUQsQ0FBRCxDQUFNZixLQUFOLEVBQXBEO0FBQ0FtQixtQkFBYSxHQUFHTyxRQUFRLENBQUNILEdBQUQsQ0FBUixHQUFnQkcsUUFBUSxDQUFDeEMsU0FBUyxHQUFHOEIsQ0FBYixDQUF4QztBQUNBbkMsT0FBQyxDQUFDa0MsRUFBRSxHQUFHLEdBQUwsR0FBV0UsT0FBWixDQUFELENBQXNCTCxXQUF0QixDQUFrQyxNQUFsQzs7QUFFQSxVQUFJTyxhQUFhLElBQUlRLGNBQWMsR0FBR3pDLFNBQVMsR0FBRyxDQUFsRCxFQUFxRDtBQUNqRGlDLHFCQUFhLEdBQUdRLGNBQWhCO0FBQ0E5QyxTQUFDLENBQUNrQyxFQUFFLEdBQUcsR0FBTCxHQUFXRyxRQUFaLENBQUQsQ0FBdUJQLFFBQXZCLENBQWdDLE1BQWhDO0FBQ0g7QUFDSjs7QUFDRDlCLEtBQUMsQ0FBQ2tDLEVBQUUsR0FBRyxHQUFMLEdBQVc5QixRQUFaLENBQUQsQ0FBdUJ3QixHQUF2QixDQUEyQixXQUEzQixFQUF3QyxnQkFBZ0IsQ0FBQ1UsYUFBakIsR0FBaUMsS0FBekU7QUFDSCxHQW5HeUIsQ0FxRzFCOzs7QUFDQSxXQUFTaEMsS0FBVCxDQUFleUMsR0FBZixFQUFvQkMsRUFBcEIsRUFBd0I7QUFDcEIsUUFBSUMsTUFBTSxHQUFHLE1BQU1qRCxDQUFDLENBQUNnRCxFQUFELENBQUQsQ0FBTXZCLE1BQU4sR0FBZUMsSUFBZixDQUFvQixJQUFwQixDQUFuQjtBQUNBLFFBQUl3QixLQUFLLEdBQUdsRCxDQUFDLENBQUNpRCxNQUFELENBQUQsQ0FBVXZCLElBQVYsQ0FBZSxZQUFmLENBQVo7QUFDQU0sZUFBVyxDQUFDZSxHQUFELEVBQU1FLE1BQU4sRUFBY0MsS0FBZCxDQUFYO0FBQ0g7QUFFSixDQTVHRCxFIiwiZmlsZSI6ImNhcnJvdXNlbC5qcyIsInNvdXJjZXNDb250ZW50IjpbIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uICgpIHtcclxuICAgIHZhciBpdGVtc01haW5EaXYgPSAoJy5NdWx0aUNhcm91c2VsJyk7XHJcbiAgICB2YXIgaXRlbXNEaXYgPSAoJy5NdWx0aUNhcm91c2VsLWlubmVyJyk7XHJcbiAgICB2YXIgaXRlbVdpZHRoID0gXCJcIjtcclxuXHJcbiAgICAkKCcubGVmdExzdCwgLnJpZ2h0THN0JykuY2xpY2soZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIHZhciBjb25kaXRpb24gPSAkKHRoaXMpLmhhc0NsYXNzKFwibGVmdExzdFwiKTtcclxuICAgICAgICBpZiAoY29uZGl0aW9uKVxyXG4gICAgICAgICAgICBjbGljaygwLCB0aGlzKTtcclxuICAgICAgICBlbHNlXHJcbiAgICAgICAgICAgIGNsaWNrKDEsIHRoaXMpXHJcbiAgICB9KTtcclxuXHJcbiAgICBSZXNDYXJvdXNlbFNpemUoKTtcclxuXHJcblxyXG5cclxuXHJcbiAgICAkKHdpbmRvdykucmVzaXplKGZ1bmN0aW9uICgpIHtcclxuICAgICAgICBSZXNDYXJvdXNlbFNpemUoKTtcclxuICAgIH0pO1xyXG5cclxuICAgIC8vdGhpcyBmdW5jdGlvbiBkZWZpbmUgdGhlIHNpemUgb2YgdGhlIGl0ZW1zXHJcbiAgICBmdW5jdGlvbiBSZXNDYXJvdXNlbFNpemUoKSB7XHJcbiAgICAgICAgdmFyIGluY25vID0gMDtcclxuICAgICAgICB2YXIgZGF0YUl0ZW1zID0gKFwiZGF0YS1pdGVtc1wiKTtcclxuICAgICAgICB2YXIgaXRlbUNsYXNzID0gKCcuaXRlbScpO1xyXG4gICAgICAgIHZhciBpZCA9IDA7XHJcbiAgICAgICAgdmFyIGJ0blBhcmVudFNiID0gJyc7XHJcbiAgICAgICAgdmFyIGl0ZW1zU3BsaXQgPSAnJztcclxuICAgICAgICB2YXIgc2FtcHdpZHRoID0gJChpdGVtc01haW5EaXYpLndpZHRoKCk7XHJcbiAgICAgICAgdmFyIGJvZHlXaWR0aCA9ICQoJ2JvZHknKS53aWR0aCgpO1xyXG4gICAgICAgICQoaXRlbXNEaXYpLmVhY2goZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICBpZCA9IGlkICsgMTtcclxuICAgICAgICAgICAgdmFyIGl0ZW1OdW1iZXJzID0gJCh0aGlzKS5maW5kKGl0ZW1DbGFzcykubGVuZ3RoO1xyXG4gICAgICAgICAgICBidG5QYXJlbnRTYiA9ICQodGhpcykucGFyZW50KCkuYXR0cihkYXRhSXRlbXMpO1xyXG4gICAgICAgICAgICBpdGVtc1NwbGl0ID0gYnRuUGFyZW50U2Iuc3BsaXQoJywnKTtcclxuICAgICAgICAgICAgJCh0aGlzKS5wYXJlbnQoKS5hdHRyKFwiaWRcIiwgXCJNdWx0aUNhcm91c2VsXCIgKyBpZCk7XHJcblxyXG5cclxuICAgICAgICAgICAgaWYgKGJvZHlXaWR0aCA+PSAxMjAwKSB7XHJcbiAgICAgICAgICAgICAgICBpbmNubyA9IGl0ZW1zU3BsaXRbM107XHJcbiAgICAgICAgICAgICAgICBpdGVtV2lkdGggPSBzYW1wd2lkdGggLyBpbmNubztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBlbHNlIGlmIChib2R5V2lkdGggPj0gOTkyKSB7XHJcbiAgICAgICAgICAgICAgICBpbmNubyA9IGl0ZW1zU3BsaXRbMl07XHJcbiAgICAgICAgICAgICAgICBpdGVtV2lkdGggPSBzYW1wd2lkdGggLyBpbmNubztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBlbHNlIGlmIChib2R5V2lkdGggPj0gNzY4KSB7XHJcbiAgICAgICAgICAgICAgICBpbmNubyA9IGl0ZW1zU3BsaXRbMV07XHJcbiAgICAgICAgICAgICAgICBpdGVtV2lkdGggPSBzYW1wd2lkdGggLyBpbmNubztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgICAgIGluY25vID0gaXRlbXNTcGxpdFswXTtcclxuICAgICAgICAgICAgICAgIGl0ZW1XaWR0aCA9IHNhbXB3aWR0aCAvIGluY25vO1xyXG4gICAgICAgICAgICAgICAgaWYoaXRlbVdpZHRoIDw9IDApe1xyXG4gICAgICAgICAgICAgICAgICAgIGl0ZW1XaWR0aCA9IGJvZHlXaWR0aCAtIDJcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAkKHRoaXMpLmNzcyh7ICd0cmFuc2Zvcm0nOiAndHJhbnNsYXRlWCgwcHgpJywgJ3dpZHRoJzogaXRlbVdpZHRoICogaXRlbU51bWJlcnMgfSk7XHJcbiAgICAgICAgICAgICQodGhpcykuZmluZChpdGVtQ2xhc3MpLmVhY2goZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgJCh0aGlzKS5vdXRlcldpZHRoKGl0ZW1XaWR0aCk7XHJcbiAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgJChcIi5sZWZ0THN0XCIpLmFkZENsYXNzKFwib3ZlclwiKTtcclxuICAgICAgICAgICAgJChcIi5yaWdodExzdFwiKS5yZW1vdmVDbGFzcyhcIm92ZXJcIik7XHJcblxyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuXHJcbiAgICAvL3RoaXMgZnVuY3Rpb24gdXNlZCB0byBtb3ZlIHRoZSBpdGVtc1xyXG4gICAgZnVuY3Rpb24gUmVzQ2Fyb3VzZWwoZSwgZWwsIHMpIHtcclxuICAgICAgICB2YXIgbGVmdEJ0biA9ICgnLmxlZnRMc3QnKTtcclxuICAgICAgICB2YXIgcmlnaHRCdG4gPSAoJy5yaWdodExzdCcpO1xyXG4gICAgICAgIHZhciB0cmFuc2xhdGVYdmFsID0gJyc7XHJcbiAgICAgICAgdmFyIGRpdlN0eWxlID0gJChlbCArICcgJyArIGl0ZW1zRGl2KS5jc3MoJ3RyYW5zZm9ybScpO1xyXG4gICAgICAgIHZhciB2YWx1ZXMgPSBkaXZTdHlsZS5tYXRjaCgvLT9bXFxkXFwuXSsvZyk7XHJcbiAgICAgICAgdmFyIHhkcyA9IE1hdGguYWJzKHZhbHVlc1s0XSk7XHJcbiAgICAgICAgaWYgKGUgPT09IDApIHtcclxuICAgICAgICAgICAgdHJhbnNsYXRlWHZhbCA9IHBhcnNlSW50KHhkcykgLSBwYXJzZUludChpdGVtV2lkdGggKiBzKTtcclxuICAgICAgICAgICAgJChlbCArICcgJyArIHJpZ2h0QnRuKS5yZW1vdmVDbGFzcyhcIm92ZXJcIik7XHJcblxyXG4gICAgICAgICAgICBpZiAodHJhbnNsYXRlWHZhbCA8PSBpdGVtV2lkdGggLyAyKSB7XHJcbiAgICAgICAgICAgICAgICB0cmFuc2xhdGVYdmFsID0gMDtcclxuICAgICAgICAgICAgICAgICQoZWwgKyAnICcgKyBsZWZ0QnRuKS5hZGRDbGFzcyhcIm92ZXJcIik7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSBpZiAoZSA9PT0gMSkge1xyXG4gICAgICAgICAgICB2YXIgaXRlbXNDb25kaXRpb24gPSAkKGVsKS5maW5kKGl0ZW1zRGl2KS53aWR0aCgpIC0gJChlbCkud2lkdGgoKTtcclxuICAgICAgICAgICAgdHJhbnNsYXRlWHZhbCA9IHBhcnNlSW50KHhkcykgKyBwYXJzZUludChpdGVtV2lkdGggKiBzKTtcclxuICAgICAgICAgICAgJChlbCArICcgJyArIGxlZnRCdG4pLnJlbW92ZUNsYXNzKFwib3ZlclwiKTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0cmFuc2xhdGVYdmFsID49IGl0ZW1zQ29uZGl0aW9uIC0gaXRlbVdpZHRoIC8gMikge1xyXG4gICAgICAgICAgICAgICAgdHJhbnNsYXRlWHZhbCA9IGl0ZW1zQ29uZGl0aW9uO1xyXG4gICAgICAgICAgICAgICAgJChlbCArICcgJyArIHJpZ2h0QnRuKS5hZGRDbGFzcyhcIm92ZXJcIik7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgJChlbCArICcgJyArIGl0ZW1zRGl2KS5jc3MoJ3RyYW5zZm9ybScsICd0cmFuc2xhdGVYKCcgKyAtdHJhbnNsYXRlWHZhbCArICdweCknKTtcclxuICAgIH1cclxuXHJcbiAgICAvL0l0IGlzIHVzZWQgdG8gZ2V0IHNvbWUgZWxlbWVudHMgZnJvbSBidG5cclxuICAgIGZ1bmN0aW9uIGNsaWNrKGVsbCwgZWUpIHtcclxuICAgICAgICB2YXIgUGFyZW50ID0gXCIjXCIgKyAkKGVlKS5wYXJlbnQoKS5hdHRyKFwiaWRcIik7XHJcbiAgICAgICAgdmFyIHNsaWRlID0gJChQYXJlbnQpLmF0dHIoXCJkYXRhLXNsaWRlXCIpO1xyXG4gICAgICAgIFJlc0Nhcm91c2VsKGVsbCwgUGFyZW50LCBzbGlkZSk7XHJcbiAgICB9XHJcblxyXG59KTsiXSwic291cmNlUm9vdCI6IiJ9