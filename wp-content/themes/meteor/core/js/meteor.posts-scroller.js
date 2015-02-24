
/* meteor.posts-scroller.js */

(function($, Core) {

Core.once('meteor-posts-scroller-init', initPostsScroller);

function initPostsScroller(targets) {
  
  targets.each(function(i, elem) {
    
    var self = $(elem);
    var row = self.find('> .row');
    var wrapper = row.find('.wrapper');
    var body = Core.body;
    var options = Core.util.parseOptions(wrapper.data('options'));
    var duration = options.duration || 500;

    var nav = $('\
<span class="nav prev"></span>\n\
<span class="nav next"></span>').css('visibility', 'hidden');

    row.append(nav);

    var prev = nav.eq(0);
    var next = nav.eq(1);
    var navHeight = prev.height();

    var items = wrapper.find('> .item');
    var frames = items.find('> div.frame');
    var columns = wrapper.data('columns');
    var parentColumns = wrapper.data('parent-columns');
    var len = items.length;
    
    Core.at('layout-change', function(layout) {
      if (layout == 'phone') {
        if (len > 2) nav.css({visibility: 'visible'});
      } else {
        if ((parentColumns/columns) === len) {
          nav.css({visibility: 'hidden'});
        } else {
          nav.css({visibility: 'visible'});
        }
      }
    });

    Core.util.loadImages(row, function() {
      
      var busy = false;
      
      var verticalCenter = function() {
        var top = Math.floor((frames.height() - navHeight)/2.0);
        nav.css('top', top + 'px');
      }
      
      // Scrolling function
      var scroll = function(direction) {
        if (busy) return; else busy = true;
        switch (direction) {
          case 'right': jc.jcarousel('scroll', '+=1'); break;
          case 'left': jc.jcarousel('scroll', '-=1'); break;
        }
        setTimeout(function() {
          busy = false;
        }, duration);
      }
      
      // Keyup function
      var keyUp = function(e) {
        switch (e.keyCode) {
          case 37: scroll('right'); break;
          case 39: scroll('left'); break;
        }
      }
      
      // Loop scroll function
      var intID;
      var loopScroll = (options.autoScroll) ? jQuery.noop : function() {
        clearInterval(intID);
        intID = setInterval(function() {
          scroll('right', true);
        }, options.autoscrollInterval || 3000);
      }
      
      // Auto scroll feature
      if (options.autoscroll) loopScroll();
      
      self.hover(function(e) {
        switch (e.type) {
          case 'mouseenter':
            if (options.autoscrollPauseOnHover) clearInterval(intID);
            body.keyup(keyUp);
            break;
          case 'mouseleave':
            if (options.autoscrollPauseOnHover) loopScroll();
            body.unbind('keyup', keyUp);
            break;
        }
      });
      
      // Centers the nav only
      verticalCenter();
      
      // This event doesn't run automatically on startup, since the event
      // is bound after the images have been loaded, which happens after 
      // the 'layout-change' event.
      
      Core.at('layout-change', verticalCenter);

      var jc = self.jcarousel({
        list: '> div.row > div.wrapper',
        items: '> div.item',
        vertical: false,
        wrap: options.wrap || 'both',
        animation: {
          duration: duration,
          easing: options.easing || 'easeInOutQuad'
        }
      });
      
      self.touchwipe({
        wipeLeft: function() {
          scroll('right');
        },
        wipeRight: function() {
          scroll('left');
        }
      });
      
      nav.click(function() {
        var btn = this.__self || (this.__self = $(this));
        scroll(btn.hasClass('prev') ? 'left' : 'right');
      });

    });

  });
}
  
})(jQuery, window.Core);