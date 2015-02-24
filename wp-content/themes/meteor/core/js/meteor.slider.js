
/* meteor.slider.js */

(function($, Core) {

var templates = {
  
  metadata_container: Hogan.compile('\n\
<div class="meteor-meta">\n\
  <div class="container clearfix">\n\
{{#nav}}\n\
    <span class="clearfix">\n\
      <a class="nav left"></a>\n\
      <a class="nav right"></a>\n\
    </span>\n\
{{/nav}}\n\
  </div><!-- .container -->\n\
</div><!-- .meteor-meta -->\n'),

  metadata: Hogan.compile('\
<a{{#url}} href="{{{url}}}"{{/url}}>\n\
  {{#title}}<h3>{{title}}</h3>{{/title}}\n\
  {{#description}}<small>{{description}}</small>{{/description}}\n\
</a>\n')

}

Core.once('meteor-slider-init', initMeteorSlider);

function initMeteorSlider(targets) {
  
  $('.meteor-slider').each(function(i, elem) {
    
    var self = $(elem);
    var slides = self.find('> ul.slides > li');
    var totalSlides = slides.length;
    var events = Core.EventEmitter();
    var options = Core.util.parseOptions(self.data('options'));
    var showNav = slides.length > 1;
    var metadata = [];
    
    self.data('events', events);
    
    var metaContainer = $(templates.metadata_container.render({
      nav: slides.length > 1
    }));
    
    var container = metaContainer.find('> .container');
    
    slides.each(function(i, elem) {
      var li = $(elem);
      metadata[i] = $(templates.metadata.render({
        url: li.data('uri'),
        title: li.data('title'),
        description: li.data('description')
      }));
    });
    
    Core.at('resize', function() {
      centerMetadata(metaContainer);
    });

    events.once('loaded', function(cycle, options) {
      
      var t = 0.9*options.speed;
      
      if (showNav) {
        
        var navLeft = metaContainer.find('> .container > span a.nav.left');
        var navRight = metaContainer.find('> .container > span a.nav.right');
        
        navLeft.click(cycle.prev);
        navRight.click(cycle.next);

      }
      
      events.on('before', function(i, direction) {
        
        var link = this.__self || (this.__self = $(this));
        
        var padding = parseInt(container.css('paddingLeft'), 10);
        
        metaContainer.addClass('moving');
        
        var incr = (direction == 'next') ? 1 : -1;
        
        var pos = Math.floor((self.width() - container.width())/2);
        
        var prop = (incr > 0) ? 'left' : 'right';
        
        if (incr > 0) {
          navRight.addClass('animating');
        } else {
          navLeft.addClass('animating');
        }
        
        setTimeout(function() {
          navRight.add(navLeft).removeClass('animating');
        }, 0.6*t);
        
        var meta = metadata[i].css({
          opacity: 0,
          position: 'absolute',
          visibility: 'visible'
        });
        
        container.prepend(meta);
        
        meta.css({
          position: 'relative',
          visibility: 'visible'
        });
        
        if (incr > 0) {
          meta.css('left', -1*(meta.outerWidth() + 2*pos));
        } else {
          meta.css('left', 3*pos + container.width());
        }
        
        var currentMeta = container.find('> a + a');
        var currentHeight = currentMeta.height();
        var newHeight = meta.outerHeight();
        var targetHeight = meta.height();
        var vposDelta = Math.floor(newHeight/2.0);
        var marginDelta = Math.floor((newHeight - currentMeta.outerHeight())/2.0);
        
        // Lock meta dimensions to avoid wrapping
        currentMeta.width(currentMeta.width());
        currentMeta.height(currentHeight);
        currentMeta.css({position: 'absolute'});
        
        // metaContainer.stop().animate({marginTop: -1*vposDelta}, t, options.easing);
        
        if (currentHeight != targetHeight) {
          
          meta.stop().height(currentHeight).animate({
            left: 0,
            opacity: 1,
            height: targetHeight
          }, t, options.easing);
            
        } else {

          meta.stop().animate({
            left: 0,
            opacity: 1
          }, t, options.easing);
            
        }
          
        var params = {
          opacity: 0,
          marginTop: marginDelta
        }
        
        if (incr > 0) {
          params.left = self.width();
        } else {
          params.left = -1*(2*pos + currentMeta.outerWidth());
        }
        
        currentMeta.css('left', padding);
        
        currentMeta.stop().animate(params, t, options.easing, function() {
          
          meta.css({
            left: 'auto',
            width: 'auto',
            height: 'auto'
          });
          
          params = {
            width: 'auto',
            height: 'auto',
            position: 'relative',
            marginTop: 'auto',
            opacity: 1
          }
          
          params[prop] = 'auto';
          
          currentMeta.detach().css(params);
          
          metaContainer.removeClass('moving');
          
          if (Core.browser.mozilla) container.removeAttr('style');
          
        });

      });
      
      container.prepend(metadata[0]);
      metaContainer.appendTo(self);
      centerMetadata(metaContainer);
      metaContainer.show();
      
    });
    
    Core.emit('slider-core-init', this);
    
  });

}

function centerMetadata(meta, height) {
  meta.css('margin-top', -1*Math.floor((meta.height()/2.0)));
}

})(jQuery, window.Core);