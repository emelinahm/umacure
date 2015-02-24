
/* core.responsive.js */

(function($, Core) {

var body = Core.body;
var win = Core.window;
var widescreen = body.data('widescreen');

win.resize(function() {
  
  var w = win.width();
  
  Core.emit('resize', w);

  if (widescreen && w >= 1200) {
    if (!body.hasClass('desktop-widescreen')) {
      Core.emit('desktop', w);
      Core.emit('layout-change', 'desktop', 'widescreen');
      body.addClass('desktop desktop-widescreen').removeClass('desktop-normal tablet phone phone-portrait phone-landscape');
    }
  } else if (w >= 980) {
    if (!body.hasClass('desktop-normal')) {
      Core.emit('desktop', w);
      Core.emit('layout-change', 'desktop', 'normal');
      body.addClass('desktop desktop-normal').removeClass('desktop-widescreen tablet phone phone-portrait phone-landscape');
    }
  } else if (w >= 767) {
    if (!body.hasClass('tablet')) {
      Core.emit('tablet', w);
      Core.emit('layout-change', 'tablet');
      body.addClass('tablet').removeClass('desktop desktop-normal desktop-widescreen phone phone-portrait phone-landscape');
    }
  } else if (w >= 480) {
    if (!body.hasClass('phone-landscape')) {
      Core.emit('phone', w);
      Core.emit('layout-change', 'phone', 'landscape');
      body.addClass('phone phone-landscape').removeClass('desktop desktop-normal desktop-widescreen tablet phone-portrait');
    }
  } else {
    if (!body.hasClass('phone-portrait')) {
      Core.emit('phone', w);
      Core.emit('layout-change', 'phone', 'portrait');
      body.addClass('phone phone-portrait').removeClass('desktop desktop-normal desktop-widescreen tablet phone-landscape');
    }
  }

});

// Add resize event on ready:
Core.once('ready:', function() {
  win.trigger('resize');
});

})(jQuery, window.Core);
