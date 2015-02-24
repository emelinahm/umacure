
/* core.util.js */

(function($, Core) {

var number = /^\d+(\.\d+)?$/;
var bool = /^(true|false)$/i;
var colorRegex = /^(#|rgb[a]?\()/i;

var themeColors = {};

Core.util = {
  
  format: _.string.sprintf,
  
  getThemeColor: function(color) {
    color = $.trim(color);
    if (colorRegex.test(color)) {
      return color;
    } else {
      color = color.replace(/^@/, '');
      if (color in themeColors) {
        return themeColors[color];
      } else {
        var elem = $('<span style="position: fixed; display: none !important;" class="'+ color +'"></span>');
        var val = themeColors[color] = elem.appendTo(Core.body).css('color'); // Clever, huh ?
        elem.remove();
        return val;
      }
    }
  },

  getHeight: function(w, h, nw) {
    return Math.floor((h*nw)/w);
  },
  
  parseOptions: function(str) {
    var target, out = {};
    var opts = (str || '').split(',');
    for (var o,k,ki,v,j,i=0,len=opts.length; i < len; i++) {
      o = opts[i];
      if (o) {
        if (o.indexOf(':') >= 0) {
          target = out;
          o = o.split(':'); k = $.trim(o[0]); v = $.trim(o[1]);
          if (k && k.indexOf('.') >= 0) {
            k = k.split('.').reverse();
            while (k.length > 1) {
              ki = $.trim(k.pop());
              if (ki in target) {
                target = target[ki];
              } else {
                target[ki] = {};
                target = target[ki];
              }
            }
            k = $.trim(k.pop());
          } else {
            k = $.trim(k);
          }
          if (number.test(v)) {
            target[k] = (v.indexOf('.') >= 0) ? parseFloat(v) : parseInt(v, 10);
          } else if (bool.test(v)) {
            target[k] = (v.toLowerCase() === 'true') ? true : false;
          } else {
            target[k] = v;
          }
        } else {
          out[$.trim(o)] = true;
        }
      }
    }
    return out;
  },
  
  randInt: function() {
    return Math.floor(Math.random()*1e6);
  },
  
  flip: function(val) {
    switch(val) {
      case 'top': return 'bottom';
      case 'bottom': return 'top';
      case 'right': return 'left';
      case 'left': return 'right';
      case 'center': return 'center';
      case 'white': return 'black';
      case 'black': return 'white';
      case 1: case '1': return -1;
      case -1: case '-1': return 1;
      case 0: return 0;
    }
  },
  
  loadImages: function(context, callback) {
    var images = _.map(context.find('img'), function(item) { return $(item).attr('src'); });
    $.imageloader({
      urls: images,
      onComplete: callback
    });
  },

  vendorPrefix: function(style) {
    var prefix = '';
    if (Core.browser.webkit) prefix = '-webkit-';
    else if (Core.browser.mozilla) prefix = '-moz-';
    else if (Core.browser.msie) prefix = '-ms-';
    else if (Core.browser.opera) prefix ='-o-';
    return prefix + style;
  }

}

})(jQuery, window.Core);
