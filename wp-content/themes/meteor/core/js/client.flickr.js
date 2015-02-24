/* global locache, hex_md5 */

/* client.flickr.js */

(function($, Core) {

Core.once('flickr-init', init);

function init(targets) {
  
  // http://www.flickr.com/services/api/flickr.photos.getSizes.html
  
  var urlSrc = Hogan.compile(Core.util.format("%s/admin-ajax.php?action=flickr_json&count={{{count}}}&display={{{display}}}&size=q&layout=x&source={{{source}}}&user={{{id}}}", Core.admin_url));

  targets.each(function(i, elem) {
    
    var self = $(elem);
    var options = Core.util.parseOptions(self.data('options'));
    
    if (!options.id) return;
    
    var url = urlSrc.render(options);
    var cacheID = Core.util.format("flickr_photostream/%s", hex_md5(url));
    var cached = locache.get(cacheID);
    var items = self.find('> ul > li')
    var links = self.find('> ul > li > a').append('<span class="overlay"></span><span class="highlight"></span>');
    
    var callback = function(images) {
      var interval = 90;
      var repl = /_t\.jpg$/;
      for (var o,link,img,len=images.length,i=0; i < len; i++) {
        o = images[i];
        link = links.eq(i);
        img = o.image.replace(repl, '_q.jpg');
        link.attr('href', o.url);
        link.find('> img').css({opacity: 0}).attr('src', img);
      }
      setTimeout(function() {
        items.each(function(i) {
          setTimeout(function() {
            links.eq(i).find('> img').stop().animate({opacity: 1}, 400);
          }, interval*(i+1));
        });
      }, 100);
    }

    if (cached) {
      callback(cached);
    } else {
      $.getJSON(url, function(images) {
        locache.set(cacheID, images, document.FLICKR_CACHE_EXPIRE || 60*60); // Cache for 1 hour
        callback(images);
      });
    }

  });
  
}
  
})(jQuery, window.Core);