require.config({
  paths: {
    jquery: "vendor/jquery/jquery",
    backbone: "vendor/backbone/backbone",
    underscore: "vendor/underscore/underscore",
    application: "app/Application",
    router: "app/Router",
    events: "app/Events",
    stateModel: "app/models/StateModel",
    mainView: "app/views/MainView",
    navView: "app/views/NavigationView",
    apartmentView: "app/views/ApartmentView",
    galleryView: "app/views/GalleryView",
    mapView: "app/views/MapView",
    raf: "app/utils/RAF"
  },
  shim: {
    backbone: {
      deps: ['jquery', 'underscore'],
      exports: 'backbone'
    },
    raf : {
      exports: 'raf'
    }
  }
});
require(
  ['jquery','application'], 
  function($, Application) {
    Application.start();
  });


// Called by Google Maps API
window.initMap = function() {
  // console.log( " Google Maps API ready " );
}
