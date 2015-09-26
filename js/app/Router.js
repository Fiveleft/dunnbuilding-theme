// app/appRouter.js
define( 
  [ 'jquery','underscore','backbone','events' ],
  function( $, _, Backbone, Events ) {

    var isApartment = false;

    var Router = Backbone.Router.extend({

      initialize: function() {
        // console.log( "Router.initialize()", this );
        var self = this;
        
        Events.on( Events.navigate, function( options ){
          var url = options.url;
          var opt = _.extend( {trigger:true}, options );
          isApartment = /apartment/.test( window.location.pathname );
          self.navigate( url, opt );
        });
      },

      routes: {
        "" : "index", //"index",
        "apartments/:type/floorplans" : "floorplans",
        "apartments/:type(/)" : "apartmentType",
        "apartments(/)" : "apartments",
        "connect" : "_loadRoute",
        "neighborhood" : "_loadRoute",
        "building-history" : "_loadRoute",
        "*page" : "_loadDefault",
      },

      index : function( route, params ) {
        this._loadRoute( "", params );
      },

      apartments : function() {
        this._loadRoute( "apartments/" );
      }, 

      apartmentType : function( type ) {
        console.log( "Router.apartmentType()", arguments );
        Events.trigger( Events.loadApartmentType, arguments );
      },

      floorplans : function() {
        console.log( "Router.floorplans()", arguments );
        Events.trigger( Events.loadApartmentSection, arguments );
      },

      _loadRoute : function( route ) {
        console.log( "Router._loadRoute()", route );
        Events.trigger( Events.loadRoute, route );
      },

      _loadDefault : function( route ) {
        console.log( "Router._loadDefault()", route );
        Events.trigger( Events.loadRoute, route );
      },

    });
    return Router;
  });