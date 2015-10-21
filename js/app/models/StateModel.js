// StateModel.js
define(
  ['backbone'],
  function( Backbone ){

    var _instance = null;


    var StateModel = Backbone.Model.extend({

      initialize: function() {
        var a = this.attributes;
        a.url = window.location.pathname;
        a.uiClick = null;
      },

      getURL : function() {
        return this.attributes.url;
      },

      setURL : function( url ) {
        this.attributes.url = (url==="" || url==="/") ? "" : url;
      },

      isHome : function() {
        return window.location.pathnanme == "/" || (/amenities/).test(this.attributes.url);
      },

      isApartment : function() {
        return (/apartments/).test(this.attributes.url);
      }

    });

    if( _instance === null ) {
      _instance = new StateModel();
    }
    return _instance;
  });