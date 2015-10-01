// MainView.js
define(
  ['jquery','events','backbone', 'stateModel', 'galleryView', 'mapView'],
  function( $, Events, Backbone, stateModel, GalleryView, MapView ){

    var _instance = null, 
      transitionEndEvents = "webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",
      $body,
      $wrapper,
      $main, 
      $mainLoader,
      mapView,
      galleryView,
      siteLinkClick = false,
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

        targetPageName = _.find( $body[0].className.split(" "), findPageNameFromClasses );
        mainTransitionDuration = 1000 * parseFloat( $body.css('transition-duration'));

        // Events.on( Events.navigate, this._routerNavigate, this );
        Events.on( Events.loadRoute, this._loadRoute, this );
        Events.on( Events.clickChatNow, this._openChat, this );
        Events.on( Events.clickRentNow, this._openRent, this );

        // Make sure loaded route has the views instantiated
        this._handleLoadedRoute();
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
        oldPageName = _.find( $body[0].className.split(" "), findPageNameFromClasses ) || "";
        targetPageName = _.find( loadedClasses, findPageNameFromClasses );

        // Set State URL
        stateModel.set( "url", window.location.pathname );

        // Do Page Transition
        startMainTransition();
      },


      /**
       * [_handleLoadedRoute description]
       * @return {[type]} [description]
       */
      _handleLoadedRoute : function() {

        var newPageView = null;
        switch( true ) 
        {
        case targetPageName === "name-home" : 
          newPageView = 'map, apartments, gallery';
          MapView.reset();
          break;
        case targetPageName === "name-neighborhood" :
          newPageView = 'map';
          MapView.reset();
          break;
        case targetPageName === "name-building-history" :
          newPageView = '[none]';
          break;
        case targetPageName === "name-apartments" :
        case $body.hasClass("single-unit_type") : 
          newPageView = 'apartments, gallery';
          break;
        }

        console.log( "MainView.handleLoadedRoute() newPageView = ", newPageView );

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
      var matches = /name\-([^\s]+)/i.exec(c);

      if( matches !== null ) {
        return matches[1];
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

      var scrollTopDelay = Modernizr.csstransitions ? mainTransitionDuration * 0.5 : 0,
        $newMain = $mainLoader.children("main");

      // Clear existing Transition Classes
      removeTransitionClasses();

      $body
        .removeClass( oldPageName )
        .addClass( 'transition-main ' + targetPageName );

      $main = $(".wrapper > main:not(.old)");
      $main
        .removeClass("new")
        .addClass("old")
        .after( $newMain );

      $newMain
        .addClass( "new" );


      _instance._handleLoadedRoute();


      if( Modernizr.csstransitions ) {
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

    if( !_instance ) {
      _instance = new MainView();
    }

    return _instance;
  });
