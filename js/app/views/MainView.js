// MainView.js
define(
  ['jquery','events','backbone'],
  function( $, Events, Backbone ){

    var transitionEndEvents = "webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",
      $body,
      $wrapper,
      $main, 
      $mainLoader,
      siteLinkClick = false,
      oldPageName,
      mainTransitionTimeout,
      mainTransitionDuration,
      scrollTopTimeout,
      targetPageView,
      targetPageName;


    var MainView = Backbone.View.extend({


      /**
       * [initialize description]
       * @return {[type]} [description]
       */
      initialize : function() {

        $body = $("body");
        $wrapper = $( "body > .wrapper" );
        $main = $(".wrapper > main");
        $mainLoader = $("body > .main-loader");

        
        targetPageName = _.find( document.body.className.split(" "), findPageNameFromClasses );
        mainTransitionDuration = 1000 * parseFloat( $body.css('transition-duration'));

        Events.on( Events.navigate, this._routerNavigate, this );
        Events.on( Events.loadRoute, this._loadRoute, this );
        Events.on( Events.clickChatNow, this._openChat, this );
        Events.on( Events.clickRentNow, this._openRent, this );
      },


      /**
       * [_openChat description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _openChat : function( e ) {
        console.log( "MainView._openChat()", e );
      },


      /**
       * [_openRent description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _openRent : function( e ) {
        console.log( "MainView._openRent()", e );
      },


      /**
       * [_routerNavigate description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _routerNavigate : function( e ) {
        console.log( "MainView._routerNavigate()", e );
      },


      /**
       * [_loadRoute description]
       * @param  {[type]} route      [description]
       * @param  {[type]} targetView [description]
       * @return {[type]}            [description]
       */
      _loadRoute : function ( route, targetView ) {
        var self = this;
        targetPageView = targetView;

        console.log( "MainView._loadRoute()", route );

        $mainLoader.load('/' + route + ' .wrapper > main', function(){ 
          self._loadRouteComplete(); 
        });
      },


      /**
       * [_loadRouteComplete description]
       * @return {[type]} [description]
       */
      _loadRouteComplete : function() {
        var $loadedMain = $mainLoader.children("main"),
          loadedClasses = $loadedMain[0].className.split(" ");

        // Define Page Target
        oldPageName = _.find( document.body.className.split(" "), findPageNameFromClasses );
        targetPageName = _.find( loadedClasses, findPageNameFromClasses );

        console.log( "MainView._loadRouteComplete() transition " + oldPageName + " > " + targetPageName );

        // Do Page Transition
        startMainTransition();
      }

    });


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // HELPER FUNCTIONS
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    
    /**
     * [findPageNameFromClasses description]
     * @param  {String} c [description]
     * @return {String}   [description]
     */
    function findPageNameFromClasses( c ) {
      if( /name-\w+/gi.test( c ) ) {
        return c;
      } 
    }


    /**
     * [findPageColorFromClasses description]
     * @param  {String} c [description]
     * @return {String}   [description]
     */
    function findPageColorFromClasses( c ) {
      if( /color-\w+/gi.test( c ) ) {
        return c;
      } 
    }


    /**
     * [removeTransitionClasses description]
     * @return {[type]} [description]
     */
    function removeTransitionClasses() {
      var removeClasses = "",
        current = document.body.className.split(" ");

      _.each( current, function( c ){
        if( c.indexOf("transition") > -1 ) {
          removeClasses += c + " ";
        }
      });
      $body.removeClass( removeClasses );
    }



    /**
     * [startMainTransition description]
     * @return {[type]} [description]
     */
    function startMainTransition() {

      var scrollTopDelay = Modernizr.csstransitions ? mainTransitionDuration * 0.5 : 0;
      var $newMain = $mainLoader.children("main");
      var transitionClasses = 'transition-main';
      transitionClasses += ' transition-from-' + oldPageName.replace( /name-/ig, '' );
      transitionClasses += ' transition-to-' + targetPageName.replace( /name-/ig, '' );

      // Clear existing Transition Classes
      removeTransitionClasses();

      $main = $(".wrapper > main:not(.old)");
      $main
        .removeClass("new")
        .addClass("old")
        .after( $newMain );

      $newMain
        .addClass( "new" );

      $("body")
        .removeClass( oldPageName )
        .addClass( targetPageName );


      if( Modernizr.csstransitions ) {
        $body.addClass( transitionClasses );
        clearTimeout( mainTransitionTimeout );
        mainTransitionTimeout = setTimeout( endMainTransition, mainTransitionDuration );

      }else{
        endMainTransition();
      }

      if( siteLinkClick ) {
        siteLinkClick = false;
        clearTimeout( scrollTopTimeout );
        scrollTopTimeout = setTimeout( function(){ $(window).scrollTop(0); }, scrollTopDelay );
      }
    }


    /**
     * [endMainTransition description]
     * @return {[type]} [description]
     */
    function endMainTransition() {
      //clearTimeout( scrollTopTimeout );
      clearTimeout( mainTransitionTimeout );

      // Clear existing Transition Classes
      removeTransitionClasses();

      $(".wrapper > main.old" ).remove();
      $(".wrapper > main.new" ).removeClass("new");
    }



    return MainView;
  });
