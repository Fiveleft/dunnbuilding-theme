// Application.js
define(
  ['jquery', 'backbone', 'underscore', 'router', 'events', 'mainView', 'navView'],
  function( $, Backbone, _, Router, Events, MainView, NavView ) {

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
