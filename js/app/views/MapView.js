// MapView.js
define(
  ['jquery','events','backbone'],
  function( $, Events, Backbone ){

    var _instance = null,
      map = null,
      coords = null,
      marker = null,
      resetQueued = false;


    var MapView = Backbone.View.extend({

      initialize : function() {
        var self = this;
        if( typeof google.maps.Map === 'undefined' ) {
          window.initMap = function() {
            // console.log( " Google Maps API ready - callback modifed inside MapView.initialize() " );
            if( resetQueued ) {
              resetQueued = false;
              self.reset();
            }
          };
        }
      },

      reset : function() {

        if( typeof google.maps.Map === 'undefined' ) {
          resetQueued = true;
          return;
        }

        if( map ) {
          console.log( map );
        }


        this.setElement( $(".wrapper > main:not(.old) .google-map") );

        coords = {
          lat : Number( this.$el.attr("data-lat") ),
          lng : Number(this.$el.attr("data-lng")),
        };

        map = new google.maps.Map( this.el, {
          center: coords,
          zoom: 14,
          scrollwheel: false,
        });

        marker = new google.maps.Marker({
          title: 'Dunn Building',
          position: coords,
          map: map,
        });
      }

    });


    if( _instance === null ) {
      _instance = new MapView();
    }

    return _instance;
  });