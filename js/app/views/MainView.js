// MainView.js
define(
  ['jquery','jqueryEffects','events','backbone', 'stateModel', 'apartmentView', 'galleryView', 'mapView', 'modalView'],
  function( $, $effects, Events, Backbone, stateModel, ApartmentView, GalleryView, MapView, ModalView ){

    var _instance = null, 
      transitionEndEvents = "webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",
      $body,
      $wrapper,
      $main, 
      $mainLoader,
      mapView,
      galleryView,
      galleries = [],
      // siteLinkClick = false,
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
        $header = $( "body > header" );
        $main = $(".wrapper > main");
        $mainLoader = $("body > .main-loader");

        targetPageName = _.find( $body[0].className.split(" "), findPageNameFromClasses );
        mainTransitionDuration = 1000 * parseFloat( $body.css('transition-duration'));

        // Events.on( Events.navigate, this._routerNavigate, this );
        Events.on( Events.loadRoute, this._loadRoute, this );
        Events.on( Events.handleLoadedRoute, this._handleLoadedRoute, this );

        // Make sure loaded route has the views instantiated
        // Events.trigger( Events.handleLoadedRoute );
        this._handleLoadedRoute();
      },


      // /**
      //  * [_routerNavigate description]
      //  * @param  {[type]} e [description]
      //  * @return {[type]}   [description]
      //  */
      // _routerNavigate : function( data ) {

      //   siteLinkClick = data.newClick === true;
      //   console.log( "MainView._routerNavigate() siteLinkClick: ", siteLinkClick );

      // },


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

        var paths = window.location.pathname.replace( /^\/|\/$/g, "" ).split("/"),
          newPageView = null;

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
        case paths[0] === "apartments" :
        case targetPageName === "name-apartments" :
        case $body.hasClass("single-unit_type") : 
          newPageView = 'apartments, gallery';
          ApartmentView.render();
          break;
        }

        for( var i=galleries.length-1; i!==-1; i-- ){
          var gv = galleries[i];
          // console.log( "Gallery[" + gv.cid + "]", gv.$el.parent() );
          if( gv.el.clientWidth < 1 ) {
            gv.remove();
            galleries.splice(i,1);
          }
        }
        $(".gallery").each( function(i,el){
          if( ! $(el).data("gallery") ) {
            galleries.push( new GalleryView({el:el}) );
          }else{
            // console.log( "Gallery[" + $(el).data("gallery").cid + "] already exists" );
          }
        });
        
        this._scrollToTarget();

      },


      /**
       * Scroll To Target
       */
      _scrollToTarget : function() {

        var paths = window.location.pathname.replace( /^\/|\/$/g, "" ).split("/"),
          scrollTo = -2,
          scrollToY = 0;

        // console.log( "MainView._scrollToTarget | paths:", paths );

        switch( true ) 
        {
        case stateModel.get("uiClick") === null :
          scrollTo = -2;
          break;
        case stateModel.get('uiClick') === false : 
          // console.log( "\t- ui click is false, allow browser to scroll to previous position ");
          break;
        case paths.length === 1 && paths[0] === "amenities" :
          // console.log( "\t- scroll to Amenities ");
          scrollTo = $( ".wrapper > main section.amenities");
          break;
        case paths.length === 1 && paths[0] === "apartments" :
          // console.log( "\t- scroll to Apartment Header ");
          scrollTo = -1;
          break;
        case paths.length === 2 && paths[0] === "apartments" :
          // console.log( "\t- scroll to Apartment Type Nav ");
          scrollTo = $( ".wrapper > main article.apartments .unit-type-nav");
          break;
        case paths.length === 3 && paths[0] === "apartments" && paths[2] === "floorplans" :
          // console.log( "\t- scroll to Floor Plans ");
          scrollTo = $( ".wrapper > main section.apartment-type-floorplans");
          break;
        default : 
          // console.log( "\t- scroll to Page Top " );
          scrollTo = 0;
          break;  
        }

        stateModel.set( "uiClick", false );


        switch( true )
        {
        case scrollTo === -2 :
          // console.log( "\t- scrollTo:", scrollTo, ", scrollToY:", scrollToY );
          break;

        case scrollTo === -1 :
          scrollToY = 0;
          // console.log( "\t- scrollTo:", scrollTo, ", scrollToY:", scrollToY );
          $(window).scrollTop( scrollToY );
          break;

        case scrollTo === 0 :
          scrollToY = 0;
          // console.log( "\t- scrollTo:", scrollTo, ", scrollToY:", scrollToY );
          $body.stop().animate({scrollTop:scrollToY}, 500, 'easeInOutCubic', function(){});   
          break;

        default :
          scrollToY = scrollTo.offset().top - $header.outerHeight();
          // console.log( "\t- scrollTo:", scrollTo, ", scrollToY:", scrollToY );
          $body.stop().animate({scrollTop:scrollToY}, 500, 'easeInOutCubic', function(){}); 
          break;
        }
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
      $main.removeClass("new")
        .addClass("old")
        .after( $newMain );

      $newMain
        .addClass( "new" );

      $main.remove();
      $mainLoader.empty();

      Events.trigger( Events.handleLoadedRoute );


      if( Modernizr.csstransitions ) {
        clearTimeout( mainTransitionTimeout );
        mainTransitionTimeout = setTimeout( endMainTransition, mainTransitionDuration );
      }else{
        endMainTransition();
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
