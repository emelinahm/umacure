
/* client.social.js */

(function($, Core) {

var whitespace = /\s+/g;
var imgRegex = /\/([^\/]+)\.png$/;
var socialClass = /social-(.*?)-(\d+) /;

Core.once('social-icons-init', function(target) {
  // var target = body.find('ul.social-icons');
  var json = Core.root + '/core/js/data/brand-hex.json.txt';
  if (target.length) $.getJSON(json, function(colors) {
    var options = Core.util.parseOptions(target.data('options'));
    initSocialIcons(target, colors, options);
  });
});

function initSocialIcons(ul, colors, options) {
  
  if (options.nobrandcolor) {
    
    ul.addClass('native');
    
  } else {

    var t = 110;

    ul.find('li').each(function(i, elem) {
      
      var self = elem.__self = $(elem);
      var link = self.find('a');
      var klass = link.attr('class');
      var name = klass.split(whitespace).pop();
      var matches = klass.match(socialClass);
      var variant = matches[1], size = matches[2];
      
      self.data({
        link: link,
        color: new Color(colors[name]).lighten(0.28).hexString(),
        opacity: link.css('opacity'),
        klass: klass + ' ' + name,
        invertedClass: (variant === 'black') ? 
          'social-' + (variant == 'black' ? 'white' : 'black') + '-' + size + ' ' + name
          : null
      });
      
    }).hover(function(e) {
      var self = this.__self;
      var data = self.data();
      var link = self.data('link');
      switch(e.type) {
        case 'mouseenter':
          link.css('opacity', 1);
          if (data.invertedClass) link.removeClass(data.klass).addClass(data.invertedClass);
          self.stop().animate({backgroundColor: data.color}, t);
        break;
        case 'mouseleave':
          link.css('opacity', data.opacity);
          if (data.invertedClass) link.removeClass(data.invertedClass).addClass(data.klass);
          self.stop().animate({backgroundColor: 'rgba(0,0,0,0)'}, t);
        break;
      }
    });
    
  }
  
}

function getSocialName(src) {
  var matches = (src || '').match(imgRegex);
  return matches ? matches[1] : null;
}

})(jQuery, window.Core)
