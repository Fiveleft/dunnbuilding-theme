// Application.js
define(
  ['jquery', 'backbone', 'router', 'events'],
  function( $, Backbone, Router, Events ) {

    var $main, 
      $siteLink;


    var Application = {

      initialize : function() {

        // Elements
        $main = $(".wrapper > main");
        $siteLink = $("[href^='" + localized.homeUrl + "'],[href^='/']");

        $siteLink.on( "click", function(e){
          e.preventDefault();
          e.stopPropagation();
          var url = $(e.currentTarget).attr("href");
          url = url.replace(/^.*\/\/[^\/]+/, '');
          Events.trigger( "router:navigate", url);
        });

        // Backbone.history.on("route", this._loadRoute );
      },

      start : function() {
        this.initialize();
        new Router();
        Backbone.history.start({pushState: true});
        
        // Events.on("router:navigate", function(url, options){
        //   var opt = _.extend( {trigger:true}, options );
        //   self.navigate( url, opt );
        // });
        Events.on( "router:loadRoute", function(  ) {
          console.log( arguments );
        });
        console.log( Events );
      },

      _loadRoute : function ( route, args ) {


        console.log( "Application.loadRoute", route, args );
      },

      _loadRouteComplete : function( args ) {
        console.log( "Application.loadRoute", args );
      }

    };
    return Application;
  });
