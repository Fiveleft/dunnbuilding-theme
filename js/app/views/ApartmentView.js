// ApartmentView.js
define(
  ['jquery','events','backbone','stateModel'],
  function( $, Events, Backbone, stateModel ){

    var $body, 
      $main,
      $mainLoader,
      $apartmentTypeNav,
      $apartmentTypeContainer,
      apartmentViewActive = false,
      apartmentType,
      apartmentSection = null,
      mainTransitionTimeout,
      mainTransitionDuration,
      i;


    var ApartmentView = Backbone.View.extend({

      initialize : function() {

        $body = $("body");
        $main = $("main");
        $mainLoader = $("body > .main-loader");
        $header = $("header.site-header");

        $apartmentTypeNav = $( "nav.unit-type-nav", $main );
        $apartmentTypeContainer = $("section.apartment-type-info");

        // 
        mainTransitionDuration = 1000 * parseFloat( $body.css('transition-duration'));

        var paths = window.location.pathname.replace( /^\/|\/$/g, "" ).split("/");
        console.log( "ApartmentView.initialize()", paths, 'local url: ', localized.homeUrl );

        apartmentType = paths.length > 1 ? paths[1] : null;
        apartmentSection = paths.length > 2 ? paths[2] : null;

        if( paths[0] === "apartments" ) {
          endMainTransition();
        }
        

        
        Events.on( Events.loadApartmentType, this._loadApartmentType, this );
        Events.on( Events.loadApartmentSection, this._loadApartmentSection, this );

      },


      /**
       * [_loadApartmentPage description]
       * @param  {[type]} url [description]
       * @return {[type]}     [description]
       */
      _loadApartmentPage : function( url ) {

        var self = this;
        // console.log( "ApartmentView._loadApartmentPage()", url );

        $mainLoader.load( url + ' .wrapper > main', function(html, response, jqXHR) {
          // If we've received a 404 page, no worries mon!
          if( response == "error" || jqXHR.status == 404 ) {
            var nodes = $.parseHTML( jqXHR.responseText );
            var wrapper_html = _.findWhere( nodes, { nodeName: "DIV", className: "wrapper" } );
            var $mainEl = $(wrapper_html).children( "main" );
            if( $mainEl ) {
              // console.log( " handle load error ", nodes, $mainEl );
              $mainEl.addClass( "name-error" );
              $mainLoader.append( $mainEl );
              self._loadApartmentPageComplete();
            }
          }else{
            self._loadApartmentPageComplete(); 
          }
        });
      },


      /**
       * [_loadApartmentPageComplete description]
       * @return {[type]} [description]
       */
      _loadApartmentPageComplete : function( ) {
        // console.log( "ApartmentView._loadApartmentPageComplete", arguments );
        stateModel.set( "url", window.location.pathname );
        startTransition();
      },


      _loadApartmentSection : function( request ) {
        // console.log( "ApartmentView._loadApartmentSection()", request );
        var loadURL = localized.homeUrl + '/apartments/' + request.type + '/' + request.section + "/";
        apartmentType = request.type;
        apartmentSection = request.section;
        this._loadApartmentPage( loadURL );
      },


      _loadApartmentType : function( request ) {
        // console.log( "ApartmentView._loadApartmentType()", request );
        var loadURL = localized.homeUrl + '/apartments/' + request.type + "/";
        apartmentType = request.type;
        apartmentSection = null;
        this._loadApartmentPage( loadURL );
      },

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
     * [startTransition description]
     * @return {[type]} [description]
     */
    function startTransition() {

      var scrollTopDelay = Modernizr.csstransitions ? mainTransitionDuration * 0.5 : 0;
      var $newMain = $mainLoader.children("main");

      var newClasses = $mainLoader.children("main")[0].className + " transition";
      document.body.className = newClasses;

      // Clear existing Transition Classes
      removeTransitionClasses();

      $oldContent = $("article.apartment-type", $main );

      $newContent = $( "article.apartment-type", $newMain );
      $newContent.addClass("new");

      $oldContent.after( $newContent );
      $oldContent.remove();

      if( Modernizr.csstransitions ) {
        clearTimeout( mainTransitionTimeout );
        mainTransitionTimeout = setTimeout( endMainTransition, mainTransitionDuration );
      }else{
        endMainTransition();
      }

      // if( siteLinkClick ) {
      //   siteLinkClick = false;
      //   clearTimeout( scrollTopTimeout );
      //   scrollTopTimeout = setTimeout( function(){ $(window).scrollTop(0); }, scrollTopDelay );
      // }
    }


    /**
     * [endMainTransition description]
     * @return {[type]} [description]
     */
    function endMainTransition() {
      //clearTimeout( scrollTopTimeout );
      clearTimeout( mainTransitionTimeout );


      var $targetScrollEl,
        elTop = 0,
        headerOffset = $header.outerHeight(),
        targetScrollY = headerOffset;

      switch( apartmentSection ) 
      {
      case null : 
        $targetScrollEl = $( "section.apartments-header");
        break;
      case 'floorplans' :
        $targetScrollEl = $( "section.apartment-type-floorplans");
        break;
      case 'building-amenities' :
        $targetScrollEl = $( "section.amenities");
        break;
      }

      console.log( "ApartmentView.endMainTransition()" );
      console.log( "\tapartmentSection = " + apartmentSection );
      console.log( "\t$targetScrollEl = ", $targetScrollEl );

      elTop = $targetScrollEl.offset().top;
      targetScrollY = elTop - headerOffset;

      console.log( "\telTop = " + elTop );
      console.log( "\theaderOffset = " + headerOffset );
      console.log( "\ttargetScrollY = " + targetScrollY );


      $(window).scrollTop( targetScrollY );


      // Clear existing Transition Classes
      removeTransitionClasses();
      $("section.apartment-type-info", $main )
        .removeClass("new");
      
    }







    return ApartmentView;
  });




