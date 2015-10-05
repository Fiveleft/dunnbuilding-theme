// app/appRouter.js
define( 
  [ 'jquery','underscore','backbone','events', 'stateModel' ],
  function( $, _, Backbone, Events, stateModel ) {

    var Router = Backbone.Router.extend({

      initialize: function() {
        var self = this;

        stateModel.set( "url", window.location.pathname );
        // console.log( "Router.initialize()", this, stateModel );
        
        Events.on( Events.navigate, function( options ){
          var url = options.url;
          var opt = _.extend( {trigger:true}, options ); 
          stateModel.set( "uiClick", (options.newClick===true) );
          self.navigate( url, opt );
        });
      },

      routes: {
        "" : "index", //"index",
        "apartments/:type/:section" : "apartmentSection",
        "apartments/:type/" : "apartmentType",
        "*page" : "_loadRoute",
      },

      index : function() {
        this._loadRoute( "" );
      },

      apartmentType : function( type ) {
        if( stateModel.isApartment() ) {
          Events.trigger( Events.loadApartmentType, { type:type } );
        }else{
          this._loadRoute( "/apartments/" + type );
        }
      },

      apartmentSection : function( type, section ) {
        if( stateModel.isApartment() ) {
          Events.trigger( Events.loadApartmentSection, { type:type, section:section } );
        }else{
          this._loadRoute( "/apartments/" + type + "/" + section );
        }
      },

      _loadRoute : function( route ) {
        // console.log( "Router._loadRoute()", route );
        Events.trigger( Events.loadRoute, route );
      },

    });
    return Router;
  });