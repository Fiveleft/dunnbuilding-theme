// MainView.js
define(
  ['jquery','events','backbone', 'stateModel'],
  function( $, Events, Backbone, stateModel ){

    var transitionEndEvents = "webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",
      $body,
      $wrapper,
      $main, 
      $mainLoader,
      siteLinkClick = false,
      apartmentView = false,
      oldPageName = "",
      mainTransitionTimeout,
      mainTransitionDuration,
      scrollTopTimeout,
      targetPageView,
      targetPageName,

      i;


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

        // Events.on( Events.navigate, this._routerNavigate, this );
        Events.on( Events.loadRoute, this._loadRoute, this );
        Events.on( Events.clickChatNow, this._openChat, this );
        Events.on( Events.clickRentNow, this._openRent, this );
      },


      /**
       * [_openChat description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _openChat : function() {
        // console.log( "MainView._openChat()", e );
      },


      /**
       * [_openRent description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _openRent : function() {
        // console.log( "MainView._openRent()", e );
      },


      /**
       * [_routerNavigate description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _routerNavigate : function( data ) {
        console.log( "MainView._routerNavigate()", data );
      },


      /**
       * [_loadRoute description]
       * @param  {[type]} route      [description]
       * @param  {[type]} targetView [description]
       * @return {[type]}            [description]
       */
      _loadRoute : function ( route ) {
        var self = this;
        
        // console.log( "MainView._loadRoute()", route );

        $mainLoader.load( localized.homeUrl + '/' + route + ' .wrapper > main', function( html, response, jqXHR ){ 
          
          // console.log( '$mainLoader.load()', response, jqXHR );
          // If we've received a 404 page, no worries mon!
          if( response == "error" || jqXHR.status == 404 ) {
            var nodes = $.parseHTML( jqXHR.responseText );
            var wrapper_html = _.findWhere( nodes, { nodeName: "DIV", className: "wrapper" } );
            var $mainEl = $(wrapper_html).children( "main" );
            if( $mainEl ) {
              // console.log( " handle load error ", nodes, $mainEl );
              $mainEl.addClass( "name-error" );
              $mainLoader.append( $mainEl );
              self._loadRouteComplete();
            }
          }else{
            self._loadRouteComplete(); 
          }
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
        oldPageName = _.find( document.body.className.split(" "), findPageNameFromClasses ) || "";
        targetPageName = _.find( loadedClasses, findPageNameFromClasses );

        // Set State URL
        stateModel.set( "url", window.location.pathname );
        // console.log( "MainView._loadRouteComplete() transition " + oldPageName + " > " + targetPageName );

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
