
/* meteor.vslider.js */

(function($, Core) {
  
Core.once('meteor-vslider-init', initVSlider);

function initVSlider(targets) {
  
  var navHtml = '\
<span>\n\
  <a class="nav prev"></a>\n\
  <a class="nav next"></a>\n\
</span>';
  
  targets.each(function(i, elem) {
    
    var self = $(elem);
    var options = Core.util.parseOptions(self.data('options'));
    var metaContainer = self.find('> .meteor-meta');
    var align = metaContainer.data('align');
    
    metaContainer.find('> .container').prepend($(navHtml));
    
    var link = metaContainer.find('> .container > a');
    var title = link.find('h3');
    var navPrev = metaContainer.find('> .container > span .nav.prev');
    var navNext = metaContainer.find('> .container > span .nav.next');
    var offset = navPrev.width();
    var sep = parseInt(metaContainer.find('> .container').css('paddingRight'), 10);
    var events = Core.EventEmitter();
    var metadata = [];
    
    self.find('> ul.slides > li').each(function(i, elem) {
      var li = $(elem);
      metadata[i] = {
        url: li.data('uri') || '',
        title: li.data('title') || ''
      }
    });
    
    self.data('events', events);
    
    if (metadata.length <= 1) return;
    
    events.once('loaded', function(cycle, options) {
      
      var busy = false;
      var body = Core.body;
      var currentSlide = 0;
      var totalSlides = metadata.length;

      if (options.effect === 'slide' && options.direction === 'vertical') {
        self.addClass('vertical');
      }
      
      var keyupCallback = function(e) {
        switch (e.keyCode) {
          case 37:
            navPrev.trigger('click', true);
            break;
          case 39:
            navNext.trigger('click', true);
            break;
        }
      }
      
      self.hover(function(e) {
        switch (e.type) {
          case 'mouseenter':
            body.bind('keyup', keyupCallback);
            break;
          case 'mouseleave':
            body.unbind('keyup', keyupCallback);
            break;
        }
      });

      navPrev.click(cycle.prev).show();
      navNext.click(cycle.next).show();
      
      Core.at('layout-change', function(layout, variant) {
        if (layout == 'phone' && variant == 'portrait') {
          if (!link.attr('href')) title.addClass('hidden-phone-portrait');
        } else {
          title.removeClass('hidden-phone-portrait');
        }
      });
      
      events.on('before', function(i, direction) {
        
        var incr = (direction == 'next') ? 1 : -1;

        (incr > 0 ? navNext : navPrev).addClass('animating');
        
        var t = options.speed;
        var params = {};
        var meta = metadata[i];
        var w = link.outerWidth() + 2*offset + 1 + sep;
        
        params[align] = -1*(w + 100);
        
        title.stop().animate(params, 0.5*t, options.easing, function() {
          
          setTimeout(function() {
            (incr > 0 ? navNext : navPrev).removeClass('animating');
          }, 140);
          
          if (meta.url) {
            link.attr('href', meta.url);
            title.removeClass('hidden-phone-portrait');
          } else {
            link.removeAttr('href');
            title.addClass('hidden-phone-portrait');
          }
  
          if (meta.title) {
            params = {}; params[align] = 0;
            title.parent().removeClass('notitle');
            title.html(meta.title);
            title.animate(params, 0.5*t, options.easing);
          } else {
            title.parent().addClass('notitle');
          }

        });
        
      });
      
    });
    
    Core.emit('slider-core-init', this);
    
  });

}
  
})(jQuery, window.Core);