// GalleryView.js
define(
  ['jquery','underscore','events','backbone','raf','utils'],
  function( $, _, Events, Backbone, RAF, Utils ){


    var defaults = {
      flickAlign : true,
      leftProp  : "transform",
      selectors : {
        wrapper : ".gallery-slides-wrapper",
        list : ".gallery-slides",
        listItem : "dl",
        advance : ".advance",
      }
    };

    // Measurements
    var m = {
      viewportWidth : 0,
      itemWidth : 0,
      listWidth : 0,
      itemCount : 0,
      x : 0,
      xMin : -1,
      xMax : 1,
      clampX : function( targetX ) {
        return Utils.clamp( targetX, this.xMin, this.xMax );
      },
      getIndexOfX : function( x ) {
        var indexAtX = (x===0) ? 0 : Math.round( Math.abs(x) / this.viewportWidth );
        return indexAtX;
      },
      getXofIndex : function( index ) {
        return -index * this.itemWidth;
      },
    };




    var GalleryView = Backbone.View.extend({

      initialize : function( cfg ) {

        var self = this;

        this.config = _.extend( defaults, cfg||{} );
        this.$galleryWrapper = $( this.config.selectors.wrapper, this.$el );
        this.$galleryList = $( this.config.selectors.list, this.$galleryWrapper);
        this.$galleryItems = $( this.config.selectors.listItem, this.$galleryList);
        this.$advanceButton = $( this.config.selectors.advance, this.$galleryWrapper);

        // Calculate; 
        this.measurements = _.extend( {}, m );
        this.index = 0;
        this.currentDist = 0;
        this.calculate();
        this.updateActive();

        // Touch Events
        this.handlers = {
          galleryEnd : function(e){self.onGalleryAdvanceEnd(e);},
          resizeCalculate : function(){ self.calculate(); }
        };
        this.$galleryList.on( Events.transitionEnd, this.handlers.galleryEnd );
        $(window).on( "resize", _.throttle( this.handlers.resizeCalculate, 500 ) );

      },


      remove : function() {
        this.$galleryList.off( Events.transitionEnd, this.handlers.galleryEnd );
        $(window).off( "resize", _.throttle( this.handlers.resizeCalculate, 500 ) );
        this.config = null;
        this.measurements = null;
      },


      events : {
        'touchstart .gallery-slides-wrapper' : 'onTouch',
        'touchend .gallery-slides-wrapper' : 'onTouch',
        'touchmove .gallery-slides-wrapper' : 'onMove',
        'click .advance' : 'onAdvanceClick'
      },


      /**
       * [calculate description]
       * @return {null}
       */
      calculate : function() {

        var m = this.measurements;

        m.viewportWidth = this.$galleryWrapper.width();
        m.listWidth = this.$galleryList.outerWidth();
        m.itemCount = this.$galleryItems.length;
        m.itemWidth = this.$galleryItems.outerWidth();

        // Slide Positions finds out how manu 
        m.slidePositions  = Math.ceil( m.listWidth / m.viewportWidth );
        m.slideMultiplier = Math.floor( m.viewportWidth / m.itemWidth );
        m.slidePositionMax = m.slidePositions - 1;

        // Max Move Positions
        m.xMin = m.viewportWidth - m.listWidth;
        m.xMax = 0;
        m.x = parseInt( this.$galleryList.css( "margin-left" ), 10 );
        m.x = m.clampX( m.x );

        this.$galleryList.css( {"margin-left" : m.x} );
        // console.log( m );
      },


      updateActive : function() {
        console.log( "GalleryView.updateActive() index: " + this.index );

        $(this.$galleryItems[this.index])
          .addClass( "active" )
          .siblings()
          .removeClass( "active" );

        this.$el
          .attr( "data-active", this.index )
          .toggleClass( "first-index", this.index===0 )
          .toggleClass( "last-index", this.index===(this.measurements.itemCount-1) );

      },



      /**
       * [onAdvanceClick description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      onAdvanceClick : function( e ) {

        e.preventDefault();
        e.stopImmediatePropagation();
      
        var self = this,
          direction = $(e.target).hasClass('next') ? 1 : -1,
          newIndex = Utils.clamp( this.index+direction, 0, this.measurements.itemCount-1 ),
          newX = this.measurements.getXofIndex( newIndex );

        console.log( "direction: " + direction + ", newIndex: " + newIndex + ", newX: " + newX );

        if( this.measurements.x !== newX ) {
          this.measurements.x = newX;
          this.$galleryItems.removeClass("active");
        }

        this.$galleryList.css({ "margin-left" : this.measurements.x });
      },


      /**
       * [onGalleryAdvanceEnd description]
       * @param  {[type]} e [description]
       * @return {[type]}   [description]
       */
      onGalleryAdvanceEnd : function( e ) {

        if( e && !$(e.originalEvent.target).is( '.gallery-slides' ) ) return;

        // e.stopImmediatePropagation();
        this.measurements.x = parseInt( this.$galleryList.css( "margin-left" ), 10 );
        this.index = this.measurements.getIndexOfX( this.measurements.x );
        this.updateActive();
      },


      /**
       * [onBreakpointChange description]
       * @return {[type]} [description]
       */
      onBreakpointChange : function() {
        //
        this.calculate();
      },


      /**
       * [onMove description]
       * @param  {Event} e [description]
       * @return {[type]}   [description]
       */
      onMove : function( e ) {

        var m = this.measurements,
          touchPos = e.originalEvent.changedTouches[0],
          totalDist = touchPos.pageX - this.startTouch.pageX,
          totalY = touchPos.pageY - this.startTouch.pageY,
          movedX = Math.abs( totalDist ),
          movedY = Math.abs( totalY ),
          newLeft = this.startTouchPx + totalDist;
        
        this.currentDist = Math.round(touchPos.pageX - this.currentTouch.pageX);
        this.currentTouch = _.extend( {}, touchPos );
        this.isVertical = (!this.isVertical) ? (movedX < 2 && movedY > 2) : this.isVertical;

        if( this.isVertical ) {
          // Don't do anything if we're scrolling vertically, 
          // console.log( " ^ MOVING VERTICALLY " );
        }else{
          // otherwise  prevent the page from scrolling
          // console.log( "< > MOVING HORIZONTAL " );
          e.preventDefault();
          e.stopPropagation();

          if( newLeft <= m.xMax && newLeft >= m.xMin ) {
            m.x = m.clampX( newLeft );
            this.$galleryList.css({ "margin-left" : m.x });
          }
        }
      },


      /**
       * [onTouch description]
       * @param  {Event} e [description]
       * @return {[type]}   [description]
       */
      onTouch : function( e ) {
        // var touchPos = e.originalEvent.changedTouches[0];
        var m = this.measurements;

        this.isTouch = (e.type === "touchstart");
        this.isFlick = (e.type === "touchend" && Math.abs(this.currentDist) > 10);
        this.isVertical = (e.type === "touchstart") ? false : this.isVertical;
        this.currentTouch = e.originalEvent.changedTouches[0];
        this.startTouch = (this.isTouch) ? _.extend( {}, this.currentTouch ) : this.startTouch;
        this.currentDist = (this.isTouch) ? 0 : this.currentDist;
        this.flickDist = this.isFlick ? this.currentDist : 0;

        switch( true ) {

        case this.isVertical : 
          // do nothing;
          break;

        case this.isTouch :
          // If starting touch
          this.startTouchPx = m.x;
          this.$galleryItems.removeClass('active');
          this.$galleryList.addClass('touch-event');
          break;

        case this.isFlick : 
          var dir = this.flickDist < 0 ? -1 : 1;
          var nextIndex = this.index - dir;
          var nextX = Utils.clamp( m.getXofIndex( nextIndex ), m.xMin, m.xMax );
          this.index = Utils.clamp( nextIndex, 0, m.itemCount-1 );
          this.flickTarget = nextX;
          // console.log( "* FLICK * nextIndex: " + this.index );
          this.doFlick();
          break;

        case !this.isTouch && !this.isFlick :
          
          var touchEndX = m.x + this.flickDist,
            endTouchTarget =  m.itemWidth * -m.getIndexOfX( touchEndX );

          if( Math.abs( endTouchTarget - m.x ) < 1 ) {
            this.updateActive();
          }

          m.x = endTouchTarget;
          this.$galleryList
            .removeClass('touch-event')
            .css({ "margin-left" : m.x });
          break;

        default :
          this.$galleryList.removeClass( 'touch-event' );
        }
      }, 


      /**
       * [doFlick description]
       * @return {[type]} [description]
       */
      doFlick : function() {
        var self = this;
        if( Math.abs(this.flickDist) > 0 ) {
          requestAnimFrame(function(){self.doFlick();});
        }else{
          this.$galleryList
            .css({ "margin-left" : this.measurements.x + "px" })
            .removeClass('touch-event');
          this.updateActive();
        }
        this.renderFlick();
      },


      /**
       * [renderFlick description]
       * @return {[type]} [description]
       */
      renderFlick : function() {
        this.flickDist = (this.flickTarget - this.measurements.x) * 0.18;
        if( Math.abs(this.flickDist) < 0.5 ) {
          this.measurements.x = this.flickTarget;
          this.flickDist = 0;
        }else {
          var nextX = this.measurements.x + this.flickDist;
          this.measurements.x = Utils.clamp( nextX, this.measurements.xMin, this.measurements.xMax );
        }
        this.$galleryList.css({ "margin-left" : Math.round( this.measurements.x ) + "px" });
      }

    });


    return GalleryView;
  });
