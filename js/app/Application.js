// Application.js
define(
  ['jquery', 'backbone', 'underscore', 'router', 'events', 'mainView', 'navView', 'apartmentView'],
  function( $, Backbone, _, Router, Events, MainView, NavView, ApartmentView ) {

    var $body;
    var Application = {

      /**
       * [initialize description]
       * @return {[type]} [description]
       */
      initialize : function() {

        // Elements
        $body = $( "body" );

        // Views
        new NavView({el:$body});
        new MainView({el:$body});
        new ApartmentView({el:$body});
      },


      /**
       * [start description]
       * @return {[type]} [description]
       */
      start : function() {
        this.initialize();
        Backbone.history.start({pushState: true});
        new Router();
      },

    };
    return Application;
  });
