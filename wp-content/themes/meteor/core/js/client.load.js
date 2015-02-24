
/* client.load.js */

(function($, Core) {

Core.once(':ready', function() {
  initMeteorSlider();
  initMeteorVSlider();
  initTwitterBar();
  initMeteorShapes();
});

Core.once('ready', function() {
  initPrettyPrint();
  initJCarousel();
  initSocialIcons();
  initFlickr();
  initTwitterWidget();
  initGoogleMaps();
  initPostsScroller();
  initMeteorForms();
  initHeaderSearch();
});

Core.once('ready:', function() {
  initLightbox();
});

function initHeaderSearch() {
  var search = $('header[role=banner] ul#navigation > li.search');
  if (search.length) Core.deps.load("client.search", function() {
    Core.emit('header-search-init');
  });
}

function initMeteorForms() {
  var targets = $('.meteor-form');
  if (targets.length) Core.deps.load(["jquery-base64", "jquery-validate", "client.forms"], function() {
    Core.emit('meteor-forms-init', targets);
  });
}

function initMeteorShapes() {
  var targets = $('canvas.meteor-shape');
  if (targets.length) Core.deps.load("core.canvas", function() {
    Core.emit('meteor-shapes-init', targets);
  });
}

function initPostsScroller() {
  var targets = $('.meteor-posts.scroller');
  if (targets.length) Core.deps.load(["jcarousel", "meteor.posts-scroller"], function() {
    Core.emit('meteor-posts-scroller-init', targets);
  });
}

function initMeteorSlider() {
  var targets = $('.meteor-slider');
  if (targets.length) Core.deps.load(["core.slider", "meteor.slider"], function() {
    Core.emit('meteor-slider-init', targets);
  });
}

function initMeteorVSlider() {
  var targets = $('.meteor-vslider');
  if (targets.length) Core.deps.load(["core.slider", "meteor.vslider"], function() {
    Core.emit('meteor-vslider-init', targets);
  });
}

function initGoogleMaps() {
  var targets = $('.meteor-google-map');
  if (targets.length) Core.deps.load("client.maps", function() {
    Core.emit('google-maps-init', targets);
  });
}

function initTwitterBar() {
  var twitterBar = $('#twitter-bar');
  if (twitterBar.length) Core.deps.load(["locache", "client.twitter"], function() {
    Core.emit('twitter-bar-init', twitterBar);
  });
}

function initTwitterWidget() {
  var targets = $('.widget.widget_meteor_twitter_feed');
  if (targets.length) Core.deps.load(["locache", "client.twitter"], function() {
    Core.emit('twitter-widget-init', targets);
  });
}

function initFlickr() {
  var targets = $('.meteor-photostream.flickr');
  if (targets.length) Core.deps.load(["locache", "md5", "client.flickr"], function() {
    Core.emit('flickr-init', targets);
  });
}

function initSocialIcons() {
  var targets = $('ul.social-icons');
  if (targets.length) Core.deps.load("client.social", function() {
    Core.emit('social-icons-init', targets);
  });
}

function initJCarousel() {
  var targets = $('.meteor-jcarousel');
  if (targets.length) Core.deps.load(["jcarousel", "meteor.jcarousel"], function() {
    Core.emit('jcarousel-init', targets);
  });
}

function initLightbox() {
  var links = $('a[rel^=lightbox]');
  if (links.length) Core.deps.load("prettyphoto", function() {
    links.prettyPhoto({
      social_tools: null,
      theme: 'pp_default',
      opacity: 0,
      slideshow: 4000,
      deeplinking: false,
      overlay_gallery: false
    });
  });
}

function initPrettyPrint() {
  var pre = $('pre.prettyprint');
  if (pre.length) {
    Core.deps.load('google-code-prettify');
  }
}

})(jQuery, window.Core);