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
        "" : "index",
        "apartments" : "apartments",
        "apartments/*path" : "apartmentType",
        "connect" : "connect",
        "neighborhood" : "neighborhood",
        "building-history" : "buildingHistory",
      },

      index : function( q, p ) {
        console.log( "Router.index()", this, q, p );
        Events.trigger( Event.loadRoute, q );
      },

      apartments : function( q, p ) {
        console.log( "Router.apartments()", this, q, p );
        Events.trigger( Event.loadRoute, q );
      }, 

      apartmentType : function( q, p ) {
        console.log( "Router.apartmentType()", this, q, p );
        Events.trigger( Event.loadRoute, q );
      },

      buildingHistory : function( q, p ) {
        console.log( "Router.buildingHistory()", this, q, p );
        // Events.trigger( Event.loadRoute, q );
      }, 

      connect : function( q, p ) {
        console.log( "Router.connect()", this, q, p );
        // Events.trigger( Event.loadRoute, q );
      },

      neighborhood : function( q, p ) {
        console.log( "Router.neighborhood()", this, q, p );
        // Events.trigger( Event.loadRoute, q );
      },

      // _scrollTo : function( element ) {
      //   if( !element ) {
      //     var target = window.location.pathname.split("/")[1];
      //     element = $("[data-scrollto='" + target + "']");
      //   }
      //   Events.trigger( Events.scrollTo, element );
      // },

      // _buildIndex : function() {
      //   if( !siteindex ) {
      //     siteindex = new SiteIndex();
      //   }
      // },

      // index : function() {
      //   this._buildIndex();
      //   this._scrollTo();
      // },

      // project : function( slug ) {
      //   this._buildIndex();
      //   Events.trigger( "project:set", slug );
      //   this._scrollTo();
      // },

    });
    return Router;
  });