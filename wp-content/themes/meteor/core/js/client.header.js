
/* client.header.js */

(function($, Core) {
  
Core.once(':ready', function() {
  initLogo();
});

Core.once('ready', function() {
  initNavigation();
  initMobileNav();
  initTopbar();
  initStickyHeader();
});

function initStickyHeader() {
  
  var body = Core.body;
  var win = Core.window;
  var doc = $(document);
  var header = $('header[role=banner]');
  var isMobileDevice = (Core.ios || Core.android);
  var adminBarHeight = $('#wpadminbar').height() || 0;
  
  if (header.data('sticky') && !isMobileDevice && !Core.ie8) {
  
    if (Core.browser.safari) header.css('-webkit-backface-visibility', 'hidden');
    
    var nav = header.find('ul#navigation');
    var navHeight = nav.find('> li').height();
    var mainLinks = nav.find('> li > a');
    var navLogo = header.find('#nav-logo');
    var logo = navLogo.find('#logo');
    var logoImage = logo.find('a:first img');
    var centered = logo.data('align') == 'center';
    var shadow = $('<div class="shadow"></div>');
    var originalPadding = parseInt(logo.css('paddingTop'), 10);
    var padding = originalPadding;
    var bottomSep = navLogo.data('nav-bottom-sep');
    var originalBottomSep = bottomSep;
    var adjust = 1;
    var dist = header.data('sticky-distance') || 500;
    var minPadding = header.data('sticky-offset') || 14;
    var mobileDisabled = header.data('sticky-mobile') === false;
    var minLogoHeight = header.data('sticky-min-logo-height') || 29;
    var pushDiv = $('<div id="pushdiv"></div>');
    var run = true;
    
    var logoW = logoImage.width();
    var logoH = logoImage.height();
    
    if (logoH < minLogoHeight) minLogoHeight = logoH;
    
    var logoDelta = logoH - minLogoHeight;
    
    if (minPadding > padding) minPadding = padding;
    
    if (centered) {
      // The links and the container must have the paddings syncronized
      mainLinks = mainLinks.add(header.find('#nav-container'));
    }
  
    if (mobileDisabled) {
      body.addClass('sticky-mobile-disabled');
      pushDiv.addClass('hidden-phone');
    }
  
    var updateBodyPadding = function(layout) {
      Core.nextTick(function() {
        var height = header.outerHeight();
        if (layout == 'phone' || body.hasClass('phone')) {
          if (!mobileDisabled) pushDiv.height(height);
        } else {
          pushDiv.height(height);
        }
      });
    }
    
    if (adminBarHeight) header.css({marginTop: adminBarHeight});
    
    var section = header.next();
    var currentPadding = section.css('paddingTop');
    
    section.css('paddingTop', header.height());
    header.addClass('sticky-enabled');
    shadow.appendTo(header);

    Core.nextTick(function() {
      updateBodyPadding();
      pushDiv.prependTo(body);
      section.css('paddingTop', currentPadding);
    });

    Core.at('layout-change', function(layout, variant) {
      if (layout != 'phone') {
        var top = win.scrollTop();
        calcPadding(win.scrollTop());
        resizeAnimation(0);
        updateBodyPadding(layout);
        mainLinks.stop().css({paddingBottom: calcLinkPadding()});
      }
    });

    var newLogoW, newLogoH;

    var calcPadding = function(top) {
      
      padding = (originalPadding - Math.floor((top*originalPadding)/dist));
      padding = (padding >= minPadding) ? padding : minPadding;
      
      if (centered) {
        bottomSep = (originalBottomSep - Math.floor((top*originalBottomSep)/dist));
        bottomSep = (bottomSep > minPadding) ? bottomSep : minPadding;
      }
      
      if (logoDelta) {
        var delta = Math.ceil((top*logoDelta)/dist);
        newLogoH = logoH - delta;
        newLogoH = (newLogoH >= minLogoHeight) ? newLogoH : minLogoHeight;
        newLogoW = Math.floor((logoW*newLogoH)/logoH);
      }

    }
    
    var calcLinkPadding = function(animating) {
      if (centered) {
        return bottomSep;
      } else {
        if (animating) {
          return Math.floor(((newLogoH + 2*padding) - navHeight)/2);
        } else {
          return Math.floor((navLogo.outerHeight() - navHeight)/2);
        }
      }
    }

    var resizeAnimation = function(timeout) {
      setTimeout(function() {
        var t = 500, easing = 'easeOutQuad';
        logo.stop().animate({padding: padding + 'px 0'}, t, easing);
        if (logoDelta) logoImage.stop().animate({width: newLogoW, height: newLogoH}, t, easing);
        mainLinks.stop().animate({paddingBottom: calcLinkPadding(true)}, t, easing);
      }, timeout);
    }
    
    doc.scroll(function(e, top) {
      top = win.scrollTop();
      if (run && top < dist) {
        calcPadding(top >= 0 ? top : 0);
        updateBodyPadding();
        logo.stop().css({padding: padding + 'px 0'});
        if (logoDelta) logoImage.stop().css({width: newLogoW, height: newLogoH});
        mainLinks.stop().css({paddingBottom: calcLinkPadding()});
      }
    });
  
    Core.once('ready:', function() {
      if (run) {
        var intID = setInterval(function() {
          var top = win.scrollTop();
          if (top >= 2) {
            clearInterval(intID);
            calcPadding(top);
            resizeAnimation(250);
            mainLinks.stop().css({paddingBottom: padding + 1});
          }
        }, 10);
        setTimeout(function() {
          clearInterval(intID);
        }, 2000);
      }
    });
  
  }

}

function initNavigation() {
  
  var t = Core.ie8 ? 0 : 100;
  var header = $('header[role=banner]');
  var navLogo = header.find('#nav-logo');
  var logo = navLogo.find('#logo');
  var navigation = navLogo.find('ul#navigation');
  var links = navigation.find('> li > a');
  var bottomSep = navLogo.data('nav-bottom-sep');
  var originalPadding = parseInt(logo.css('paddingTop'), 10);
  
  if (Core.browser.safari) {
    navigation.css({marginTop: -12});
  }
  
  navigation.removeClass('fallback');
  
  navigation.find('> li:last-child').addClass('last-child');
  
  var updateLinkPadding = function() {
    
    if (logo.data('align') == 'center') {
    
      var padding = header.find('#nav-container').css('paddingBottom');
      links.css({paddingBottom: padding});
    
    } else {
    
      Core.nextTick(function() {
        var toplevel = navigation.find('> li:first');
        var offset = Math.floor((logo.outerHeight() - toplevel.height())/2);
        links.css({paddingBottom: offset});
      });
    
    }

  }
  
  updateLinkPadding();
  
  if (!header.data('sticky')) {
    
    // If the header is not sticky, manually adjust the logo on mobile view
    Core.at('layout-change', function(layout, variant) {
      if (layout == 'phone') {
        logo.css({padding: bottomSep + 'px 0'});
      } else {
        logo.css({padding: originalPadding + 'px 0'});
        updateLinkPadding();
      }
    });

  }
  
  navigation.find('> li > ul > li:first-child').each(function(i, elem) {
    var li = this.__self = $(elem);
    var tip = $('<span class="tip"></span>');
    var parent = li.parents('li');
    li.prepend(tip);
    parent.addClass('has-tip').data('tip', tip);
    parent.data('a', parent.find('> a:first'));
    tip.data('offset', parseInt(tip.css('border-left-width'), 10));
  });

  navigation.find('li').each(function(i, elem) {
    
    var li = this.__self || (this.__self = $(elem));
    var ul = li.find('ul:first');
    if (ul.length) {
      li.addClass('submenu');
      li.data('ul', ul);
    }

  }).hover(function(e) {

    var li = this.__self;
    var ul = li.data('ul');
    if (ul && ul.length) {
      switch (e.type) {
        case 'mouseenter':
          if (li.hasClass('has-tip')) {
            var tip = li.data('tip');
            var offset = (li.data('a').outerWidth()/2.0) - tip.data('offset');
            tip.css('left', offset);
          }
          ul.stop().fadeIn(t, 'easeOutQuad');
          break;
        case 'mouseleave':
          ul.stop().hide();
          break;
      }
    }

  });
  
}

function initLogo() {
  var logo = $('header[role=banner] #logo');
  var links = logo.find("> a");
  if (links.length === 2) {
    if (Core.retina) {
      logo.find('.retina-hide').remove();
    } else {
      logo.find('.retina-show').remove();
    }
  }
}

function initMobileNav() {
  var header = $('header[role=banner]');
  var container = header.find('#nav-logo > .container');
  var logo = header.find('#nav-logo #logo');
  var nav = header.find('ul#navigation');
  var mobileNav = Core.ui.createMobileNav(nav.clone(), {id: "mobile-nav"});
  var padding = header.find('#nav-logo').data('nav-bottom-sep');
  mobileNav.attr('class', 'visible-phone');
  container.append(mobileNav);
  mobileNav.after('<div class="visible-phone" style="width: 100%; height: '+ parseInt(padding, 10) +'px;"></div>');
}

function initTopbar() {
  
  var topbar = $('header[role=banner] #topbar > .container');
  
  if (topbar.length) {

    var aside = topbar.find('> aside');
    var social = topbar.find('> ul.social-icons');
    
    if ($.trim(aside.html()).length === 0 || social.children().length === 0) {
      return; // Nothing to do
    }
    
    var noshow = aside.hasClass('noshow') ? aside : social;
    var where = noshow.data('align'), current = where;
    
    var elements = {
      left: topbar.find('> [data-align=left]'),
      right: topbar.find('> [data-align=right]')
    }
    
    var toggleBtn = $('<span class="togglebtn visible-phone"></span>');
    var t = 200, busy = false;
    
    toggleBtn.addClass(where);
    
    toggleBtn.click(function() {
      if (busy) return; else busy = true;
      var flipped = Core.util.flip(current);
      elements[flipped].stop().fadeOut(t);
      toggleBtn.fadeOut(t, function() {
        elements[flipped].addClass('noshow').removeAttr('style');
        elements[current].hide().removeClass('noshow').fadeIn(t);
        toggleBtn.removeClass(current).addClass(flipped).fadeIn(t, function() {
          current = flipped;
          busy = false;
        });
      });
    });
    
    topbar.append(toggleBtn);

  }

}
  
})(jQuery, window.Core);