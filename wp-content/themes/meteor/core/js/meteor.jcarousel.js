
/* meteor.jcarousel.js */

(function($, Core) {
  
Core.once('jcarousel-init', init);
  
function init(targets) {
  
  var body = Core.body;
  
  targets.each(function(i, elem) {
    
    var busy = false;
    var nav, self = $(elem).addClass('jcarousel');
    var options = Core.util.parseOptions(self.data('options'));
    var duration = options.duration || 500;
    var autoCenter, row = self.find('> ul.row');

    Core.util.loadImages(row, function() {
      
      // Get vertical setting
      var height, vertical = (options.direction === 'vertical');
      
      if (vertical === false) {

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
        
        // Loop scroll function
        var intID;
        var loopScroll = (options.autoScroll) ? jQuery.noop : function() {
          clearInterval(intID);
          intID = setInterval(function() {
            scroll('right', true);
          }, options.autoscrollInterval || 3000);
        }
        
        // Append navigation
        self.after('<span class="meteor-jcarousel-nav next"></span>');
        self.after('<span class="meteor-jcarousel-nav prev"></span>');
        
        // Get nav
        nav = self.siblings('span.meteor-jcarousel-nav');
        
        var links = self.find('ul > li > a');
        
        nav.click(function() {
          if (busy) return;
          var btn = this.__self || (this.__self = $(this));
          var incr = btn.hasClass('next') ? 'right' : 'left';
          links.trigger('mouseleave');
          scroll(incr);
        });
        
        self.touchwipe({
          wipeLeft: function() {
            links.trigger('mouseleave');
            scroll('right');
          },
          wipeRight: function() {
            links.trigger('mouseleave');
            scroll('left');
          }
        });
        
        var keyUp = function(e) {
          switch (e.keyCode) {
            case 37: scroll('right'); break;
            case 39: scroll('left'); break;
          }
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

        var loading = 2;
        
        // Automatically center items vertically
        (autoCenter = function() {
          if (loading) loading--;
          height = row.height();
          self.height(height);
          row.find('> li > *:first-child').each(function(i, elem) {
            var item = $(elem);
            var tm = Math.floor((height - item.outerHeight())/2.0);
            item.stop().animate({marginTop: tm}, loading ? 0 : 380, 'easeOutQuad');
            nav.stop().css({marginTop: -1*height + Math.floor((height - nav.height())/2.0)});
          });
        }).call(this);
        
        // Auto resize on layout change
        Core.at('layout-change', autoCenter);
        
      }
      
      var jc = self.jcarousel({
        list: '> ul',
        items: '> li',
        vertical: vertical,
        wrap: options.wrap || 'circular',
        animation: {
          duration: duration,
          easing: options.easing || 'easeInOutQuad'
        }
      });

    });
    
  });

}
  
})(jQuery, window.Core);