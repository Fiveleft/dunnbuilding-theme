// app/appRouter.js
define( 
  [ 'jquery','underscore','backbone','events' ],
  function( $, _, Backbone, Events ) {

    var Router = Backbone.Router.extend({

      initialize: function() {
        console.log( "Router.initialize()", this );
        var self = this;
        
        Events.on("router:navigate", function(url, options){
          var opt = _.extend( {trigger:true}, options );
          self.navigate( url, opt );
        });
      },

      routes: {
        "" : "index", //"index",
        ":apartments" : "_loadRoute", //"apartments",
        ":apartments/:path" : "apartmentType",
        ":apartments/:path/:section" : "apartmentSection",
        "connect" : "_loadRoute", //"connect",
        "neighborhood" : "_loadRoute", //"neighborhood",
        "building-history" : "_loadRoute", //"buildingHistory",
        "*page" : "_loadRoute", //"defaultRoute",
      },

      index : function( route, params ) {
        this._loadRoute( "", params );
      },

      apartments : function( route, params ) {
        this._loadRoute( route, params );
      }, 

      apartmentType : function( route, params ) {
        console.log( "Router.apartmentType()", this, route, params );
        this._loadRoute( route + '/' + params );
      },

      apartmentSection : function( a, b, c ) {
        console.log( "Router.apartmentSection()", a, b, c );
      },

      buildingHistory : function( route, params ) {
        this._loadRoute( route, params );
        //Events.trigger( Event.loadRoute, q );
      }, 

      connect : function( route, params ) {
        // console.log( "Router.connect()", this, route, params );
        this._loadRoute( route, params );
        //Events.trigger( Event.loadRoute, q );
      },

      neighborhood : function( route, params ) {
        // console.log( "Router.neighborhood()", this, route, params );
        this._loadRoute( route, params );
        //Events.trigger( Event.loadRoute, q );
      },

      defaultRoute : function( route, params ) {
        // console.log( "Router.defaultRoute()", this, route, params );
        this._loadRoute( route, params );
        //Events.trigger( Event.loadRoute, q );
      },

      _loadRoute : function( route, params ) {
        console.log( "Router._loadRoute()", route, params );
        Events.trigger( Events.loadRoute, route );
      },

    });
    return Router;
  });