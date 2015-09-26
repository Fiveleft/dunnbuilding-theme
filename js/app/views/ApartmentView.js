// ApartmentView.js
define(
  ['jquery','events','backbone'],
  function( $, Events, Backbone ){

    // var transitionEndEvents = "webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",
    //   navOpen = false,
    //   navClosedCallback = null,
    //   urlRegex,
    //   siteLink;


    var ApartmentView = Backbone.View.extend({

      initialize : function() {

        // var self = this;


        this.$body = $("body");
        this.$main = $("main");
        this.$apartmentTypeNav = $( "nav.unit-type-nav", this.$main );

        var paths = window.location.pathname.replace( /^\/|\/$/g, "" ).split("/");
        console.log( "ApartmentView.initialize()", paths );
        
        Events.on( Events.loadApartmentType, this._loadApartmentType, this );
        Events.on( Events.loadApartmentSection, this._loadApartmentSection, this );

        
      },


      _loadApartmentSection : function( section ) {
        console.log( "ApartmentView._loadApartmentSection()", section );

      },


      _loadApartmentType : function( section, type ) {
        console.log( "ApartmentView._loadApartmentType()", section, type );
      },


    });


    return ApartmentView;
  });