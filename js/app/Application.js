// Application.js
define(
  ['jquery', 'backbone', 'underscore', 'router', 'events'],
  function( $, Backbone, _, Router, Events ) {

    var $body,
      $wrapper,
      $main, 
      $mainLoader,
      urlRegex,
      siteLinkClick = false,
      oldPageName,
      mainTransitionTimeout,
      mainTransitionDuration,
      scrollTopTimeout,
      targetPageView,
      targetPageName;


    var Application = {

      /**
       * [initialize description]
       * @return {[type]} [description]
       */
      initialize : function() {

        // Elements
        $body = $( "body" );
        $wrapper = $( "body > .wrapper" );
        $main = $(".wrapper > main");
        $mainLoader = $(".wrapper > .main-loader");
        
        // Vars
        urlRegex = new RegExp( localized.homeUrl );
        targetPageName = _.find( document.body.className.split(" "), findPageNameFromClasses );
        mainTransitionDuration = 1000 * parseFloat( $body.css('transition-duration'));

        // console.log( mainTransitionDuration );
        //targetPageView;

        // Events
        $body.on( "click", "[href^='" + localized.homeUrl + "'],[href^='/']", function(e){
          e.preventDefault();
          e.stopPropagation();
          siteLinkClick = true;
          var href = $(e.currentTarget).attr("href"),
            url = href.replace( urlRegex, '');

          Events.trigger( "router:navigate", url );
        });

        Events.on( "router:loadRoute", this._loadRoute, this );
        // Backbone.history.on("route", this._loadRoute );
      },


      /**
       * [start description]
       * @return {[type]} [description]
       */
      start : function() {
        this.initialize();
        Backbone.history.start({pushState: true});
        new Router();
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

        console.log( "Application._loadRouteComplete() transition " + oldPageName + " > " + targetPageName );

        // Do Page Transition
        startMainTransition();
      }

    };


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
      // console.log( "removeTransitionClasses()\n\t" + removeClasses );
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


    return Application;
  });
