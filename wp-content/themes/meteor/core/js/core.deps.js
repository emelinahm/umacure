
/* core.deps.js */

(function($, Core) {

var loader;
var loaded = {};
var loading = {};
var events = Core.EventEmitter();

if (Core.ie8) {
  
  loader = function(scripts, callback) {
    var cb, load = [].concat(scripts);
    $.getScript(load.shift(), cb = function() {
      if (load.length === 0) {
        callback();
      } else {
        loader(load, cb);
      }
    });
  }

} else {
  
  loader = function(scripts, callback) {
    head.js.apply(null, scripts.concat(callback));
  }
  
}

Core.deps = {
  
  load: function(dep, callback) {
    if (dep instanceof Array) { var cb;
      this.load(dep.shift(), cb = function() {
        if (dep.length === 0) {
          callback();
        } else {
          Core.deps.load(dep, cb);
        }
      });
    } else if (dep in loading) {
      events.once(dep, callback);
    } else if (dep in loaded) {
      callback();
    } else if (dep in this) {
      loading[dep] = true;
      events.once(dep, callback);
      
      loader(_.map(this[dep], resolve), function() {
        delete loading[dep];
        loaded[dep] = true;
        events.emit(dep);
      });
      
    } else {
      throw new Error("Unable to load: " + dep);
    }
  },
  
  "google-code-prettify": [
    "core/js/google-code-prettify/run_prettify.js"
  ],

  "jcarousel": [
    "core/js/jcarousel/jquery.jcarousel.js"
  ],
  
  "jquery-base64": [
    "core/js/jquery/jquery.base-sixty-four.js"
  ],

  "jquery-validate": [
    "core/js/jquery/jquery.validate.js",
    "core/js/jquery/jquery.validate-extras.js"
  ],
  
  "locache": [
    "core/js/lib/locache.js"
  ],
  
  "md5": [
    "core/js/lib/md5.js"
  ],
  
  "mediaelement": [
    "core/js/mediaelement/mediaelement-and-player.min.js"
  ],
  
  "prettyphoto": [
    "core/js/prettyphoto/jquery.prettyphoto.js"
  ],
  
  "videojs": [
    "core/js/video-js/video.js"
  ],
  
  "core.canvas": [
    "core/js/core.canvas.js"
  ],
  
  "core.slider": [
    "core/js/core.slider.js"
  ],
  
  "client.flickr": [
    "core/js/client.flickr.js"
  ],
  
  "client.forms": [
    "core/js/client.forms.js"
  ],
  
  "client.maps": [
    "core/js/client.maps.js"
  ],
  
  "client.search": [
    "core/js/client.search.js"
  ],
  
  "client.social": [
    "core/js/client.social.js"
  ],
  
  "client.twitter": [
    "core/js/client.twitter.js"
  ],
  
  "meteor.jcarousel": [
    "core/js/meteor.jcarousel.js"
  ],
  
  "meteor.posts-scroller": [
    "core/js/meteor.posts-scroller.js"
  ],
  
  "meteor.slider": [
    "core/js/meteor.slider.js"
  ],
  
  "meteor.vslider": [
    "core/js/meteor.vslider.js"
  ]

}

function resolve(path) {
  return Core.path(path, true);
}

})(jQuery, window.Core);