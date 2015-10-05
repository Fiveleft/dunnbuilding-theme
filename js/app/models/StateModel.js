// StateModel.js
define(
  ['backbone'],
  function( Backbone ){

    var _instance = null;


    var StateModel = Backbone.Model.extend({

      initialize: function() {
        var a = this.attributes;
        a.url = window.location.pathname;
        a.uiClick = false;
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