// NavigationView.js
define(
  ['jquery','events','backbone'],
  function( $, Events, Backbone ){

    var transitionEndEvents = "webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",
      navOpen = false,
      navClosedCallback = null,
      urlRegex,
      siteLink;


    var NavView = Backbone.View.extend({

      initialize : function() {

        var self = this;

        this.$body = $("body");
        this.$nav = $("nav.main-nav");
        this.$navInner = $(".nav-inner", this.$nav);

        navOpen = false;
        navClosedCallback = null;
        urlRegex = new RegExp( localized.homeUrl );
        siteLink = "[href^='" + localized.homeUrl + "'],[href^='/']";

        // Events
        this.$body.on( "click", "[href^='" + localized.homeUrl + "'],[href^='/']", function(e){ 
          self._clickNavLink(e); 
        });
      },


      /**
       * Backbone View Event definitions
       * @type {Object}
       */
      events : {
        'click button.menu-toggle' : '_toggleNav',
        'click nav.main-nav button.close' : '_toggleNav',
        'click [href="#amenities"]' : '_clickAmenities',
        'click [href="#rent-now"], button.rent-now' : '_clickRentNow',
        'click [href="#live-chat"], button.live-chat' : '_clickChatNow',
      },

      /**
       * [_clickNavLink description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _clickAmenities : function( e ) {

        e.preventDefault();
        e.stopImmediatePropagation();
        // navClosedCallback = null;
        // Events.trigger( "router:navigate", {url:"/", newClick:true} );
      },
        

      /**
       * [_clickNavLink description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _clickNavLink : function( e ) {
        
        
        var self = this;
        var target = $(e.currentTarget);
        var href = $(e.currentTarget).attr("href");
        var url = href.replace( urlRegex, '').replace( /^\//, "" );

        if( target.attr("target" ) ){
          return true;
        }

        e.preventDefault();
        e.stopImmediatePropagation();
        // Clean up URL
        
        console.log( "url: " + url);

        if( self.$body.hasClass('nav-open') ) {
          navClosedCallback = function(){
            Events.trigger( "router:navigate", {url:url, newClick:true} );
          };
          self._toggleNav();
        } else {
          navClosedCallback = null;
          Events.trigger( "router:navigate", {url:url, newClick:true} );
        }
      },


      /**
       * [_toggleNav description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _toggleNav : function( e ) {
        // console.log( "NavigationView._toggleNav ");
        var self = this;
        
        if( e ) e.preventDefault();
        
        this.$nav
          .removeAttr( "aria-hidden" )
          .removeAttr( "hidden" )
          .removeClass( 'opened' )
          .addClass( 'opening' )
          .one( transitionEndEvents, function( e ){ self._toggleNavComplete( e ); } );
        
        this.tick = setTimeout( function(){
          self.$body.toggleClass( 'nav-open' );
          clearTimeout( this.tick );
        }, 60 );
      },


      /**
       * [_toggleNavComplete description]
       * @return {[type]} [description]
       */
      _toggleNavComplete : function() {
        // console.log( "NavigationView._toggleNavComplete");

        if( this.$body.hasClass('nav-open') ) {
          this.$nav
            .addClass('opened opening')
            .removeAttr( "aria-hidden" )
            .removeAttr( "hidden" ) 
            .focus();
        }else{
          this.$nav
            .css({'height' : '' })
            .removeClass('opened opening')
            .attr( "aria-hidden", true )
            .attr( "hidden", true );

          if( navClosedCallback !== null ) {
            navClosedCallback();
          }
          // this.$mainNavOpen.focus();
        }
      },


      /**
       * [_clickChatNow description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _clickChatNow : function( e ) {
        e.preventDefault();
        // console.log("NavigationView._clickChatNow()", e );
        // var self = this;
        if( this.$body.hasClass('nav-open') ) {
          navClosedCallback = function(){
            Events.trigger( Events.clickChatNow );
          };
          this._toggleNav();
        } else {
          navClosedCallback = null;
          Events.trigger( Events.clickChatNow );
        }
      },


      /**
       * [_clickRentNow description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _clickRentNow : function( e ) {
        e.preventDefault();
        // console.log("NavigationView._clickRentNow()", e );
        // var self = this;
        if( this.$body.hasClass('nav-open') ) {
          navClosedCallback = function(){
            Events.trigger( Events.clickRentNow );
          };
          this._toggleNav();
        } else {
          navClosedCallback = null;
          Events.trigger( Events.clickRentNow );
        }
      },

    });

    return NavView;
  });