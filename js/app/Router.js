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

      index : function( q, p ) {
        this._loadRoute( "" );
      },

      apartments : function( q, p ) {
        this._loadRoute( q );
      }, 

      apartmentType : function( q, p ) {
        console.log( "Router.apartmentType()", this, q, p );
        this._loadRoute( q + "/" + p );
      },

      apartmentSection : function( a, b, c ) {
        console.log( "Router.apartmentSection()", a, b, c );
      },

      buildingHistory : function( q, p ) {
        this._loadRoute( q );
        //Events.trigger( Event.loadRoute, q );
      }, 

      connect : function( q, p ) {
        // console.log( "Router.connect()", this, q, p );
        this._loadRoute( q );
        //Events.trigger( Event.loadRoute, q );
      },

      neighborhood : function( q, p ) {
        // console.log( "Router.neighborhood()", this, q, p );
        this._loadRoute( q );
        //Events.trigger( Event.loadRoute, q );
      },

      defaultRoute : function( q, p ) {
        // console.log( "Router.defaultRoute()", this, q, p );
        this._loadRoute( q );
        //Events.trigger( Event.loadRoute, q );
      },

      _loadRoute : function( route ) {
        // console.log( "Router._loadRoute()", route );
        Events.trigger( Events.loadRoute, route );
      },

    });
    return Router;
  });