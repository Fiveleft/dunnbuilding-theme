// app/appRouter.js
define( 
  [ 'jquery','underscore','backbone','events', 'stateModel' ],
  function( $, _, Backbone, Events, stateModel ) {

    var Router = Backbone.Router.extend({


      /**
       * [initialize description]
       * @return {[type]} [description]
       */
      initialize: function() {
        stateModel.setURL( window.location.pathname );
        Events.on( Events.navigate, this._navigate, this );
      },


      /**
       * [routes description]
       * @type {Object}
       */
      routes: {
        "" : "index", //"index",
        "amenities/" : "index", //"index",
        "apartments/:type/:section" : "apartmentSection",
        "apartments/:type/" : "apartmentType",
        "*page" : "_loadRoute",
      },


      /**
       * [_navigate description]
       * @param  {[type]} options [description]
       * @return {[type]}         [description]
       */
      _navigate : function( options ) {
        var opt = _.extend( {trigger:true}, options ); 

        console.log( "Router._navigate()\n\t- opt:", opt );
        
        stateModel.set( "uiClick", (opt.newClick===true) );

        if( stateModel.getURL() === opt.url ) {
          console.log( "\t- stateModel.getURL() : ", (stateModel.isHome() ? "'' (home)" : stateModel.getURL() ) );
          Events.trigger( Events.handleLoadedRoute );
        }

        this.navigate( opt.url, opt );
      },


      /**
       * [index description]
       * @return {[type]} [description]
       */
      index : function() {
        if( stateModel.isHome() ) {
          console.log( " already at home " );
          Events.trigger( Events.handleLoadedRoute );
        } else {
          this._loadRoute( "" );
        }
      },


      /**
       * [apartmentType description]
       * @param  {[type]} type [description]
       * @return {[type]}      [description]
       */
      apartmentType : function( type ) {
        if( stateModel.isApartment() ) {
          Events.trigger( Events.loadApartmentType, { type:type } );
        }else{
          this._loadRoute( "/apartments/" + type );
        }
      },


      /**
       * [apartmentSection description]
       * @param  {[type]} type    [description]
       * @param  {[type]} section [description]
       * @return {[type]}         [description]
       */
      apartmentSection : function( type, section ) {
        if( stateModel.isApartment() ) {
          Events.trigger( Events.loadApartmentSection, { type:type, section:section } );
        }else{
          this._loadRoute( "/apartments/" + type + "/" + section );
        }
      },


      /**
       * [_loadRoute description]
       * @param  {[type]} route [description]
       * @return {[type]}       [description]
       */
      _loadRoute : function( route ) {
        // console.log( "Router._loadRoute()", route );
        Events.trigger( Events.loadRoute, route );
      },



    });
    return Router;
  });