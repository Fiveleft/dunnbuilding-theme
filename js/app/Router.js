// app/appRouter.js
define( 
  [ 'jquery','underscore','backbone','events' ],
  function( $, _, Backbone, Events ) {

    var Router = Backbone.Router.extend({

      initialize: function() {
        console.log( "Router.initialize()", this );
        var self = this;
        
        Events.on("router:navigate", function( options ){
          var url = options.url;
          var opt = _.extend( {trigger:true}, options );
          self.navigate( url, opt );
        });
      },

      routes: {
        "" : "index", //"index",
        "apartments/:path/:section" : "apartmentSection",
        "apartments/:path" : "apartmentType",
        "apartments" : "_loadRoute",

        "connect" : "_loadRoute",
        "neighborhood" : "_loadRoute",
        "building-history" : "_loadRoute",
        "*page" : "_loadRoute",
      },

      index : function( route, params ) {
        this._loadRoute( "", params );
      },

      apartments : function( route, params ) {
        this._loadRoute( route, params );
      }, 

      apartmentType : function( route, params ) {
        console.log( "Router.apartmentType()", this, route, params );
        Events.trigger( Events.loadApartmentType, arguments );
        // this._loadRoute( route + '/' + params );
      },

      apartmentSection : function( a, b, c ) {
        console.log( "Router.apartmentSection()", arguments );
        Events.trigger( Events.loadApartmentSection, arguments );
      },

      _loadRoute : function( route, params ) {
        console.log( "Router._loadRoute()", route, params );
        Events.trigger( Events.loadRoute, route );
      },

    });
    return Router;
  });