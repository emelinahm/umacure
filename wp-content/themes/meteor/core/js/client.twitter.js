/* global locache, hex_md5 */

/* client.twitter.js */

(function($, Core) {

var regexes = {
  link: /((https?|s?ftp|ssh)\:\/\/[^"\s<>]*[^.,;'">\:\s<>\)\]\!])/ig,
  mention: /@([a-z0-9_]+)/ig,
  hash: /#([a-z0-9_]+)/ig
}

var repls = {
  link: '<a class="ext-link" target="_blank" href="$&">$&</a> ',
  mention: '<a class="user" target="_blank" href="http://twitter.com/$1">@$1</a>',
  hash: '<a class="hashtag" target="_blank" href="http://twitter.com/search/$1">#$1</a>'
}

Core.once('twitter-bar-init', function(twitterBar) {
  initTwitterBar(twitterBar);
  initTwitterBarFeed(twitterBar);
});

Core.once('twitter-widget-init', initTwitterWidget);

function initTwitterWidget(targets) {
  
  var template = Hogan.compile('\
<ul>\n\
  {{#tweets}}\n\
  <li>\n\
    <p class="tweet">{{{text}}}</p>\n\
    <span class="time"><a target="_blank" href="{{{link}}}">{{{reltime}}}</a></span>\n\
  </li>\n\
  {{/tweets}}\n\
</ul>');

  targets.each(function(i, elem) {
    var self = $(elem);
    var container = self.find('.post-content');
    var data = self.find('.twitter-feed-opts').data();
    if (data.username) {
      getTweets(data.username, data.count, "twitterwidget", function(tweets) {
        for (var i=0,len=tweets.length; i < len; i++) {
          tweets[i].reltime = _.string.capitalize(relativeTime(tweets[i].timestamp));
        }
        container.html(template.render({tweets: tweets}));
      });
    }
  });

}

function initTwitterBarFeed(twitterBar) {
  if (twitterBar.length) {
    var data = Core.util.parseOptions(twitterBar.data('twitterbar-options'));
    if (data.username && data.count > 0) {
      var speed = 400;
      getTweets(data.username, data.count, "twitterbar", function(tweets) {
        if (tweets && tweets instanceof Array && tweets.length > 0) {
          for (var len=tweets.length,i=0; i < len; i++) {
            tweets[i].text += Core.util.format('<small class="time"><span>&mdash;</span>%s</small>', relativeTime(tweets[i].timestamp));
          }
          var p = twitterBar.find('p.tweet').hide().html(tweets[0].text).show();
          var prev = twitterBar.find('.nav-wrap span.prev');
          var next = twitterBar.find('.nav-wrap span.next');
          var cycle = Core.Cycle(tweets.length, function(i, state, direction) {
            state.lock();
            p.fadeOut(speed/2, function() {
              p.html(tweets[i].text).fadeIn(speed/2, state.unlock);
            });
          });
          prev.click(cycle.prev);
          next.click(cycle.next);
          cycle.touchTarget(twitterBar);
          if (data.pauseOnHover) cycle.hoverTarget(twitterBar);
          if (data.autoplay) cycle.loop(data.delay);
        }
      });
    }
  }
}

function initTwitterBar(twitterBar) {
  
  var self, timeout1, timeout2;
  
  if (twitterBar.length) {
    
    twitterBar.append('\
<div class="nav-wrap">\n\
  <span class="nav prev"></span>\n\
  <span class="nav next"></span>\n\
</div>');

    var easing, t = 180;
    var win = Core.window;
    var widescreen = Core.body.data('widescreen');
    var animated = false;
    var narrow = false;
    var container = twitterBar.find('.container');
    var icon = container.find('a.twitter-icon');
    var prev = twitterBar.find('.nav-wrap > span.prev');
    var next = twitterBar.find('.nav-wrap > span.next');
    var offset = Math.abs(parseInt(prev.css('left'), 10));
    
    var iconHover = false;
    
    icon.hover(function(e) {
      switch (e.type) {
        case 'mouseenter':
          iconHover = true;
          clearTimeout(timeout1);
          clearTimeout(timeout2);
          break;
        case 'mouseleave':
          setTimeout(function() {
            iconHover = false;
          }, 100);
          break;
      }
    });
    
    twitterBar.hover(function(e) {
      
      if (!self) self = $(this);
      
      switch (e.type) {
        
        case 'mouseenter':
          timeout1 = setTimeout(function() {
            if (iconHover) return;
            var p, w = win.width();
            animated = true;
            easing = 'easeOutExpo';
            prev.stop().animate({left: 12}, t, easing);
            next.stop().animate({right: 12}, t, easing);
            timeout2 = setTimeout(function() {
              if ((w >= 480 && w <= 540) || (w >= 768 && w <= 826) || (w >= 980 && w <= 1036) || (widescreen && w >= 1200 && w <= 1256)) {
                narrow = true;
                icon.stop().fadeOut(300);
              }
            }, t);
          }, 150);
          break;
          
        case 'mouseleave':
          clearTimeout(timeout1);
          clearTimeout(timeout2);
          if (animated) {
            easing = 'easeInExpo';
            prev.stop().animate({left: -1*offset}, t, easing);
            next.stop().animate({right: -1*offset}, t, easing);
            if (narrow) {
              setTimeout(function() {
                icon.stop().fadeIn(300);
                container.stop().animate({padding: 0}, t);
                narrow = false;
              }, t);
            }
            animated = false;
          }
          break;
      }
      
    });
    
  }

}

function getTweets(username, count, context, callback) {
  
  try { // Prevent errors from breaking anything
    
    var cacheKey = Core.util.format("%s/%s/%d", context, username, count);
  
    var cached = locache.get(cacheKey);
  
    if (cached) {
    
      callback(cached);
    
    } else {
    
      var url = Core.util.format("%s/admin-ajax.php?action=twitter_api&username=%s&count=%d", Core.admin_url, username, count);
    
      $.getJSON(url, function(tweets) {
        for (var len=tweets.length,i=0; i < len; i++) {
          tweets[i] = {
            text: tweets[i].text.replace(regexes.link, repls.link).replace(regexes.mention, repls.mention).replace(regexes.hash, repls.hash),
            timestamp: tweets[i].created_at,
            link: Core.util.format("https://twitter.com/%s/status/%s", username, tweets[i].id_str)
          }
        }
        locache.set(cacheKey, tweets, document.TWEET_CACHE_EXPIRE || 5*60); // Cache for 5 minutes
        callback(tweets);
      });
    
    }
  
  } catch (e) {
    
    Core.log("Unable to retrieve tweets. Error below");
    Core.log(e);
    
  }
  
}

function relativeTime(timestamp, now) {
  if (typeof now == 'undefined') now = new Date();
  var date = new Date(timestamp);
  var delta = Math.floor((now.valueOf() - date.valueOf())/1000);
  var i18n = Core.i18n;
  if (delta < 60) {
    return i18n.lt_minute_ago;
  } else if (delta < 120) {
    return i18n.abt_minute_ago;
  } else if (delta < (60*60)) {
    return Core.util.format(i18n.minutes_ago, parseInt(delta/60, 10));
  } else if (delta < (120*60)) {
    return i18n.abt_hour_ago;
  } else if (delta < (24*60*60)) {
    return Core.util.format(i18n.abt_hours_ago, parseInt(delta/3600, 10));
  } else if (delta < (48*60*60)) {
    return i18n.one_day_ago;
  } else {
    return Core.util.format(i18n.days_ago, parseInt(delta/86400, 10));
  }
}

})(jQuery, window.Core);