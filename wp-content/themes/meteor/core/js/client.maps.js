
/* client.maps.js */

/* global google */

(function($, Core) {

// https://developers.google.com/maps/documentation/javascript/examples
// https://developers.google.com/maps/documentation/javascript/reference
// view-source:https://google-developers.appspot.com/maps/documentation/javascript/examples/infowindow-simple
// https://developers.google.com/maps/documentation/javascript/reference#MapOptions
// https://developers.google.com/maps/documentation/javascript/reference#MapTypeId

var body = Core.body;
var oldBrowser = Core.ie8;
var modernBrowser = !oldBrowser;

Core.once('google-maps-init', initGoogleMaps);

function initGoogleMaps(targets) {
  var counter = 1;
  targets.each(function(i, elem) {
    var self = elem.__self || (elem.__self = $(elem));
    self.addClass('google-map-' + counter);
    self.data('index', counter);
    var json = self.find('span.map-data').html().trim();
    json = json.slice(0, json.lastIndexOf('}') + 1);
    var options = JSON.parse(json);
    var content = self.find('.infobox-content');
    if (content.length > 0) options.content = content.html();
    counter++;
    Core.maps.create(self, options);
  });
}

Core.maps = {
  
  create: function(target, opts) {
    
    // Extend options
    opts = _.extend({
      latlong: '48.858391,2.294083',
      type: 'ROADMAP',
      zoom: 16,
      title: '',
      link: '',
      tooltip: '',
      content: '',
      contentWidth: 250,
      fullscreen: true,
      extraOptions: { }
    }, opts || {});
    
    var myLatlng = getLatLong(opts.latlong);
    
    var myOptions = _.extend({
      zoom: opts.zoom,
      scrollwheel: false,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId[opts.type]
    }, opts.extraOptions);
    
    var map = new google.maps.Map(target.get(0), myOptions);

    // Show marker only if title & content are set

    if (opts.title && opts.content) {
      
      var content = (opts.link) ? Core.util.format('<h3 style="margin-top: 0;"><a class="title" href="%s">%s</a></h3>\n%s', opts.link, opts.title, opts.content)
      : Core.util.format('<h3 style="margin-top: 0;">%s</h3>\n%s', opts.title, opts.content);
      
      var infowindow = new google.maps.InfoWindow({
          maxWidth: opts.contentWidth,
          content: content
      });

      var marker = new google.maps.Marker({
        title: opts.tooltip || opts.title,
        position: myLatlng,
        map: map
      });

      google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
      });

    }
    
    // Recenter map on window resize4
    
    var emitter = Core.EventEmitter();

    Core.at('resize', function() {
      emitter.trigger('resize');
    })

    emitter.on('resize', function() {
      var center = map.getCenter();
      google.maps.event.trigger(map, 'resize');
      map.setCenter(center);
    });

    // Fullscreen map
    
    if (opts.fullscreen && ! Core.ios ) {
      
      var btn = $('<span class="fw-button">Full Screen</span>');
      
      btn.data('parent', target);
      
      var coords = {top: 60, right: 5};

      btn.css(coords).css({
        display: 'block',
        padding: '0 8px',
        position: 'absolute',
        height: '20px',
        lineHeight: '20px',
        background: 'white',
        border: 'solid 1px #707a84',
        boxShadow: '0 2px 4px rgba(0,0,0,0.39)',
        zIndex: 999999999,
        fontFamily: 'Arial, sans-serif',
        textRendering: 'none',
        fontSize: '12px',
        userSelect: 'none',
        webkitUserSelect: 'none',
        mozUserSelect: 'none'
      });
      
      // Relocate fullscreen button on streetview

      var sv = map.getStreetView();
      
      google.maps.event.addListener(sv, 'visible_changed', function() {
        if (sv.getVisible()) btn.css({top: 2, right: 34});
        else btn.css(coords);
      });
      
      if (modernBrowser) {
        btn.hover(function(e) {
          var self = this.__self || (this.__self = $(this));
          switch (e.type) {
            case 'mouseenter':
              self.css({cursor: 'pointer', background: Core.util.vendorPrefix('linear-gradient(top, white, #e6e6e6)'), color: 'black'});
              break;
            case 'mouseleave':
              self.css({cursor: 'default', background: 'white', color: '#323232'});
              break;
          }
        });
      } else {
        btn.hover(function(e) {
          var self = this.__self || (this.__self = $(this));
          switch (e.type) {
            case 'mouseenter': self.css({cursor: 'pointer'}); break;
            case 'mouseleave': self.css({cursor: 'default'}); break;
          }
        });
      }
      
      btn.click(function(e) {
        var self = this.__self || (this.__self = $(this));
        var parent = self.data('parent');
        var index = parent.data('index');
        if (target.hasClass('full-screen')) {
          // Minimize
          self.html('Full Screen');
          target.removeClass('full-screen');
          body.unbind('keyup', keyupCallback);
          
          // Show others
          $('.meteor-google-map').not('.google-map-' + index).show();
          
        } else {
          
          // Maximize
          self.html('Normal');
          target.addClass('full-screen');
          body.keyup(keyupCallback);
          
          // Hide others
          $('.meteor-google-map').not('.google-map-' + index).hide();
          
        }
        emitter.trigger('resize');

      });
      
      var keyupCallback = function(e) {
        if (e.keyCode === 27) { // Escape
          btn.click();
        }
      }
      
      target.append(btn);

    }
    
    target.data('map', map);
    
    return target;
    
  }
  
}

function getLatLong(str) {
  if (str) {
    str = str.split(',');
    return new google.maps.LatLng(parseFloat(str[0]), parseFloat(str[1]));
  } else {
    return null;
  }
}

})(jQuery, window.Core);