/* global videojs */

/* client.media.js */

(function($, Core) {

if (Core.ie8) return;

Core.once('ready', function() {
  initVideo();
  initAudio();
});

function initAudio() {
  var audio = $('.post-audio audio');
  if (audio.length && !Core.ios) {
    Core.deps.load('mediaelement', function() {
      audio.mediaelementplayer();
    });
  }
}

function initVideo() {
  
  var browser = Core.browser;
  var videos = $('.post-video video.video-js');
  var nativeSupport = Core.ios || (Core.options.native_video_support && (Core.browser.chrome || Core.browser.safari || Core.browser.mozilla || Core.browser.opera));

  if (videos.length) {
    
    if (nativeSupport) {
      
      if (Core.ios) {
        
        videos.each(function(i, v) {
          var resizeVideo;
          var video = $(v).removeAttr('width');
          var container = video.parent();
          var width = video.width();
          var height = video.height();
          var ratio = (height/width);
          video.attr('width', '100%');
          (resizeVideo = function() {
            video.height(video.width()*ratio);
          }).call(this);
          Core.at('layout-change', resizeVideo);
        });
        
      }
      
    } else {
      
      Core.deps.load('videojs', function() {
        videojs.options.flash.swf = Core.path("core/js/video-js/video-js.swf", true);
        videos.each(function(i, elem) {
          var video = $(elem);
          videojs(video.attr('id'), {}, function() {
            var resizeContainer;
            var container = video.parents('.video-js');
            var controls = container.find('.vjs-control-bar');
            (resizeContainer = function() {
              container.height(video.height());
            }).call(this);
            Core.at('layout-change', resizeContainer);
          });
        });
      });
      
    }
    
  }
  
}

})(jQuery, window.Core);