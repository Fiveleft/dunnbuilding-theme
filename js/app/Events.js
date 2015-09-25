// app/events.js
define(
  ['underscore','backbone'],
  function(_, Backbone){

    function whichTransitionEvent(){
      var t;
      var el = document.createElement('fakeelement');
      var transitions = {
        'transition':'transitionend',
        'OTransition':'oTransitionEnd',
        'MozTransition':'transitionend',
        'WebkitTransition':'webkitTransitionEnd'
      };
      for(t in transitions){
        if( el.style[t] !== undefined ){
          return transitions[t];
        }
      }
    }


    var transitionEndEvent = whichTransitionEvent();

    var o = {
      navigate : "router:navigate",
      loadRoute : "router:loadRoute",
      clickChatNow : "click:chatNow",
      clickRentNow : "click:rentNow",
      breakpoint : "breakpoint:change",
      changeHeight : "DOM:changeHeight",
      scrollTo : "window:scrollTo",
      scrollToEnd : "window:scrollToEnd",
      transitionEnd : transitionEndEvent
    };
    _.extend( o, Backbone.Events );


    return o;
  });