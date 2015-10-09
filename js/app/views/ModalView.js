// ModalView.js
define(
  ['jquery','events','backbone'],
  function( $, Events, Backbone ){

    var _instance = null,
      isOpen = false,
      $body,
      $overlay,
      $modalWrapper,
      $loading;


    var ModalView = Backbone.View.extend({

      initialize : function() {
        
        var self = this;

        $body = $("body");
        $overlay = $(".overlay-container");
        $modalWrapper = $(".modal-wrapper", $overlay);
        $loading = $("progress", $overlay);

        Events.on( Events.clickChatNow, this._openChat, this );
        Events.on( Events.clickRentNow, this._openRent, this );
        Events.on( Events.loadRoute, this._close, this );

        this.setElement( $overlay[0] );

        // $overlay.on( "click", function(e){ self._clickContainer(e); });
        // $overlay.on( "click", "button.close", function(e){ self._clickClose(e); });
      },


      events: {
        "click" : "_clickContainer",
        "click button.close" : "_clickClose",
        "click button.cancel" : "_clickClose",
      },


      /**
       * [_open description]
       * @return {[type]} [description]
       */
      _open : function() {

        if( isOpen ) return;
        var self = this;

        isOpen = true;
        $modalWrapper.one( Events.transitionEnd, this._openComplete );
        $body.addClass( "modal-opening" );

        this.tick = setTimeout( function(){
          $body.addClass( 'modal-open' );
          clearTimeout( self.tick );
        }, 60 );

      },


      _openComplete : function() {
        // console.log( "ModalView._openComplete");
        clearTimeout( this.tick );
      },


      /**
       * [_close description]
       * @return {[type]} [description]
       */
      _close : function() {
        if( !isOpen ) return;
        $body.removeClass( "modal-open" );
        $modalWrapper.one( Events.transitionEnd, this._closeComplete );
      },


      _closeComplete : function() {
        isOpen = false;
        // console.log( "ModalView._closeComplete");
        $body.removeClass( "modal-opening" );
      },


      /**
       * [_loading description]
       * @return {[type]} [description]
       */
      _loading : function () {

      },


      /**
       * [_clickClose description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _clickClose : function( e ) {
        e.preventDefault();
        e.stopImmediatePropagation();
        // console.log( "ModalView._clickClose");
        this._close();
      },


      /**
       * [_clickContainer description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _clickContainer : function( e ) {
        if( $(e.target).is( $modalWrapper ) || $modalWrapper.find( $(e.target) ).length > 0 ) {
          // console.log( "  > Click inside modal wrapper " );
        }else{
          // console.log( "  > Click OUTSIDE modal wrapper " );
          e.preventDefault();
          this._close();
        }
      },


      /**
       * [_openChat description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _openChat : function() {
        this._open();
        // console.log( "MainView._openChat()", e );
      },


      /**
       * [_openRent description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      _openRent : function() {
        this._open();
        // console.log( "MainView._openRent()", e );
      },

    });


    if( _instance === null ) {
      _instance = new ModalView();
    }

    return _instance;
  });