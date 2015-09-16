// app/events.js
define(
  ['underscore','backbone'],
  function(_, Backbone){
    var o = {
      loadRoute : "router:loadRoute",
      breakpoint : "breakpoint:change",
      changeHeight : "DOM:changeHeight",
      scrollTo : "window:scrollTo",
      scrollToEnd : "window:scrollToEnd",
      transitionEnd : "webkitTransitionEnd MozTransitionEnd oTransitionEnd transitionend",
    };
    _.extend( o, Backbone.Events );
    return o;
  });