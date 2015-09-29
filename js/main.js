 require.config({
  paths: {
    jquery: "vendor/jquery/jquery",
    backbone: "vendor/backbone/backbone",
    underscore: "vendor/underscore/underscore",
    // utils: "fiveleft/core/Utils",
    // sharrre: "libs/jquery.sharrre",
    application: "app/Application",
    router: "app/Router",
    events: "app/Events",
    stateModel: "app/models/StateModel",
    mainView: "app/views/MainView",
    navView: "app/views/NavigationView",
    apartmentView: "app/views/ApartmentView",
    // breakpoints: "app/Breakpoints",
    // imageload: "app/ImageLoad",
    // newscrawler: "app/modules/NewsCrawler",
    // archiveview: "app/views/ArchiveView",
    // commentsview: "app/views/CommentsView",
    // homeview: "app/views/HomeView",
    // pageview: "app/views/PageView",
    // postview: "app/views/PostView",
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
