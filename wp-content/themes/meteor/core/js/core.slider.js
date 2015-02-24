
/* core.slider.js */

(function($, Core) {
  
Core.on('slider-core-init', function(elem) {
  new Slider(elem);
});

function Slider(elem) {
  
  var slider = $(elem);
  var slides = slider.find('> ul.slides li');
  
  if (slides.length === 0) return null;
  
  var data = _.map(slides, function(elem) { return $(elem); });
  var container = slides.eq(0).parent();
  var options = Core.util.parseOptions(slider.data('options'));
  var events = slider.data('events') || Core.EventEmitter();
  
  options = _.extend({
    effect: 'fade',
    speed: 750,
    timeout: 4000,
    easing: 'easeInOutQuad',
    direction: 'horizontal',
    reverse: false,
    autoplay: false,
    random: false,
    pauseOnHover: true
  }, options);
  
  slides.eq(0).addClass('current').siblings().detach();
  
  var cycle = Core.Cycle(slides.length, function(i, state, direction) {
    
    state.lock();
    
    events.emit('before', i, direction);

    var current = slides.eq(state.fromIndex);
    var next = data[i].addClass('next');
    
    container.append(next);
     
    switch (options.effect) {
      
      case 'fade':
        current.stop().animate({opacity: 0}, options.speed, options.easing, function() {
          next.removeClass('next').addClass('current');
          current.detach().removeClass('current').removeAttr('style');
          state.unlock();
        });
        break;
      
      case 'slide':
        var distance = (options.direction == 'horizontal') ? container.width() : container.height();
        var params = {}, reverse = options.reverse ? -1 : 1;
        var prop = (options.direction == 'horizontal') ? 'left' : 'top';
        if (direction == 'next') {
          params[prop] = -1*reverse*distance;
          next.css(prop, reverse*distance);
        } else {
          next.css(prop, -1*reverse*distance);
          params[prop] = reverse*distance;
        }
        current.stop().animate(params, options.speed, options.easing, function() {
          next.removeClass('next').addClass('current');
          current.detach().removeClass('current').removeAttr('style');
          state.unlock();
        });
        params = {}; params[prop] = 0;
        next.stop().animate(params, options.speed, options.easing);
        break;
    
    }
     
  });
  
  Core.util.loadImages(slides, function() {
    
    if (options.random) cycle.random();
    if (options.autoplay) cycle.loop(options.timeout);
    if (options.pauseOnHover) cycle.hoverTarget(slider);
  
    cycle.touchTarget(slider);
    cycle.keyboardTarget(slider);
    
    events.emit('loaded', cycle, options);
    
  });
  
  return cycle;
  
}

})(jQuery, window.Core);