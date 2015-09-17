// Application.js
define(
  ['jquery', 'backbone', 'underscore', 'router', 'events'],
  function( $, Backbone, _, Router, Events ) {

    var $body,
      $wrapper,
      $main, 
      $mainLoader,
      $siteLink,
      urlRegex,
      oldPageName,
      mainTransitionTimeout,
      scrollTopTimeout,
      targetPageView,
      targetPageName;


    var Application = {

      initialize : function() {

        // Vars
        urlRegex = new RegExp( localized.homeUrl );
        targetPageName = _.find( document.body.className.split(" "), findPageNameFromClasses );
        //targetPageView;
        
        // Elements
        $body = $( "body" );
        $wrapper = $( "body > .wrapper" );
        $main = $(".wrapper > main");
        $mainLoader = $(".wrapper > .main-loader");
        $siteLink = $("[href^='" + localized.homeUrl + "'],[href^='/']");

      
        // Events
        $siteLink.on( "click", function(e){
          e.preventDefault();
          e.stopPropagation();

          var href = $(e.currentTarget).attr("href"),
            url = href.replace( urlRegex, '');

          Events.trigger( "router:navigate", url);
        });

        Events.on( "router:loadRoute", this._loadRoute, this );
        // Backbone.history.on("route", this._loadRoute );
      },

      start : function() {
        this.initialize();
        Backbone.history.start({pushState: true});
        new Router();
      },

      _loadRoute : function ( route, targetView ) {
        var self = this;
        targetPageView = targetView;

        $mainLoader.load('/' + route + ' .wrapper > main', function(){ 
          self._loadRouteComplete(); 
        });
      },

      _loadRouteComplete : function() {
        var $loadedMain = $mainLoader.children("main"),
          loadedClasses = $loadedMain[0].className.split(" ");

        // Define Page Target
        oldPageName = _.find( document.body.className.split(" "), findPageNameFromClasses );
        targetPageName = _.find( loadedClasses, findPageNameFromClasses );

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


    function startMainTransition() {

      var $newMain = $mainLoader.children("main");
      var transitionClasses = 'transition-main';
      transitionClasses += ' transition-from-' + oldPageName.replace( /name-/ig, '' );
      transitionClasses += ' transition-to-' + targetPageName.replace( /name-/ig, '' );

      // Clear existing Transition Classes
      removeTransitionClasses();

      $main = $(".wrapper > main:not(.old)");
      $main
        .addClass("old")
        .after( $newMain );
      $newMain.addClass( "new" );

      $("body")
        .removeClass( oldPageName )
        .addClass( targetPageName );



      if( Modernizr.csstransitions ) {
        $body.addClass( transitionClasses );
        clearTimeout( mainTransitionTimeout );
        mainTransitionTimeout = setTimeout( endMainTransition, 1000 );
      }else{
        endMainTransition();
      }

      //clearTimeout( scrollTopTimeout );
      //scrollTopTimeout = setTimeout( function(){ $(window).scrollTop(0); }, 500 );
    }

    function endMainTransition() {
      //clearTimeout( scrollTopTimeout );
      clearTimeout( mainTransitionTimeout );

      // Clear existing Transition Classes
      removeTransitionClasses();

      $mainOld = $(".wrapper > main.old" );
      $mainNew = $(".wrapper > main.new" );

      $mainOld.remove();
      $mainNew.removeClass("new");

    }




    return Application;
  });
