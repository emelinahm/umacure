
/* client.js */

(function($, Core) {

Core.once(':ready', function() {
  this.ui.addContent();
  this.ui.readyUp('.meteor-icon-posts');
  this.ui.initFrames('.meteor-posts:not(.meteor-gallery), .meteor-gallery.standalone, .meteor-aside-posts, .post-listing');
  this.ui.initRows('[class^=meteor-], footer > .container');
  this.ui.lastChild('.widget');
  this.ui.initTooltips('.tooltips');
  this.ui.removeLastChildMargins([
    '.meteor-content',
    '.tab-content',
    '.post-content',
    '.post-content > .inner-content',
    '.comment-content',
    '.meteor-aside-posts div.excerpt',
    '.standout',
    '.meteor-notification > .content',
    '.no-child-margins',
    'blockquote'
  ].join(', '));
  initTestimonials();
  initComments();
  initMeteorGalleries();
  initBackfaceVisibility();
});

Core.once('ready', function() {
  initDefaultEffects();
  initToggle();
  initClients();
  initFooter();
  initTabs();
  initAccordion();
  initSkills();
  initNotifications();
  initIconBigShortcode();
});

function initBackfaceVisibility() {
  if (Core.browser.webkit) {
    var targets = $('.meteor-revslider-container, .meteor-cuteslider-container, .meteor-layerslider-container');
    if (targets.length) Core.body.css('backface-visibility', 'hidden');
  }
}

function initIconBigShortcode() {
  $('.meteor-icon-big').each(function(i, elem) {
    
    var self = $(elem);
    var wrapper = self.find('> .wrapper');
    var borderContainer = self.find('span.border-container');
    var icon = wrapper.find('i');
    var css = {}, data = wrapper.data();
    var themeColor = Core.util.getThemeColor;
    var transparent = 'rgba(0,0,0,0)';
    var iconColor = icon.css('color') || transparent;
    var borderWidth = borderContainer.css('borderWidth') || 0;
    
    var t = 250;
    var easing = 'swing';
    
    icon.removeClass('black black1 black2 black3 black4 black5 black6 black7 accent accent1 accent2').css('color', iconColor);

    borderContainer.css({
      borderColor: (data.borderColor) ? themeColor(data.borderColor) : transparent
    });

    wrapper.css({
      backgroundColor: (data.backgroundColor) ? themeColor(data.backgroundColor) : transparent
    }).hover(function(e) {
      var icss = {}, wcss = {}, bcss = {};
      switch (e.type) {
        case 'mouseenter':
          if (data.hoverColor) icss.color = themeColor(data.hoverColor);
          if (data.hoverBackground) wcss.backgroundColor = themeColor(data.hoverBackground);
          if (data.hoverBorderColor) bcss.borderColor = themeColor(data.hoverBorderColor);
          if (data.hoverBorderWidth) bcss.borderWidth = data.hoverBorderWidth;
          icon.stop().animate(icss, t, easing);
          wrapper.stop().animate(wcss, t, easing);
          borderContainer.stop().animate(bcss, t, easing);
          break;
        case 'mouseleave':
          icon.stop().animate({color: iconColor});
          wrapper.stop().animate({
            backgroundColor: (data.backgroundColor) ? themeColor(data.backgroundColor) : transparent
          }, t, easing);
          borderContainer.stop().animate({
            borderWidth: borderWidth,
            borderColor: (data.borderColor) ? themeColor(data.borderColor) : transparent
          }, t, easing);
          break;
      }
    });
    
  });
}

function initNotifications() {
  $('.meteor-notification.dismiss')
  .append('<span class="close"><i class="icon-remove"></i></span>')
  .delegate('> span.close', 'click', function(e) {
    $(this).parents('.meteor-notification').remove();
    e.preventDefault();
  });
}

function initMeteorGalleries() {
  
  $('.meteor-gallery').each(function(i, elem) {
    
    var self = $(elem);
    
    var rows = self.find('> .row-fluid').each(function(i, row) {
      (row = this.__self = $(row)).data('children', row.children());
    });

    var addClasses = true;
    var firstRow = rows.eq(0);
    var lastRows = firstRow.siblings();
    var items = rows.find('> .item');
    
    Core.at('layout-change', function(layout, variant) {
      if (layout == 'phone') {
        items.appendTo(firstRow);
        if (addClasses) {
          firstRow.find('.item:odd').addClass('odd');
          addClasses = false;
        }
      } else {
        rows.each(function(i, row) {
          row.__self.data('children').appendTo(row.__self);
        });
      }
    });
    
  });

}

function initComments() {
  
  var commentlist, comments = $('.meteor-post > .meteor-comments');
  
  if (comments.length) {
    
    // Set comment badges
    commentlist = comments.find('> ol.commentlist');
    commentlist.find('li.comment:last-child').addClass('last-child');
    commentlist.find('li.comment.bypostauthor .comment-meta span.fn').append('<span class="meta-tag">'+ Core.i18n.author +'</span>');
    commentlist.find('li.comment.comment-author-admin:not(.bypostauthor) .comment-meta span.fn').append('<span class="meta-tag">'+ Core.i18n.admin +'</span>');
    commentlist.find('li.comment .comment-content').addClass('post-content');
    
    // Set comment respond
    var body = Core.body;
    var respond = comments.find('#respond');
    var replyLink = commentlist.find('li.comment a.comment-reply-link');
    var closeReply = respond.find('span.respond-close a');
    
    var keyUp = function(e) {
      if (e.keyCode == 27) {
        closeReply.trigger('click');
      }
    }
    
    replyLink.click(function() {
      var comment = respond.parent();
      body.bind('keyup', keyUp);
      replyLink.css({visibility: 'hidden'});
      if (comment.hasClass('last-child')) {
        if (comment.hasClass('depth-1')) {
          if (respond.next().length === 0) {
            respond.addClass('no-bottom-sep');
          }
        } else {
          var parents = respond.parents('li.comment');
          for (var parent,i=0,len=parents.length; i < len; i++) {
            parent = parents.eq(i);
            if (parent.hasClass('last-child')) continue; else break;
          }
          if (i == len && parent.hasClass('depth-1')) {
            respond.addClass('no-bottom-sep');
          }
        }
      }
    });
    
    closeReply.click(function() {
      respond.removeClass('no-bottom-sep');
      body.unbind('keyup', keyUp);
      replyLink.css({visibility: 'visible'});
      respond.find('span.cancel-reply-link a#cancel-comment-reply-link').trigger('click');
    });
    
  }
  
}

function initDefaultEffects() {
  if (Core.ios) $('.meteor-icon-posts a.circle-frame').hover(function(e) {
    var self = this.__self || (this.__self = $(this));
    switch (e.type) {
      case 'mouseenter': self.addClass('hover');  break;
      case 'mouseleave': self.removeClass('hover'); break;
    }
  });
}

function initTestimonials() {
  
  $('.meteor-testimonials').each(function(i, elem) {
    
    var cycle, self = $(elem).addClass('running');
    var options = Core.util.parseOptions(self.data('options'));
    var ul = self.find('> ul');
    var items = ul.find('> li');
    var details = items.find('> p.details');
    var len = items.length;
    
    if (len <= 1) return;

    var nav = details.append('\
<span class="nav next"></span>\n\
<span class="nav prev"></span>').find('span.nav');

    var t = Core.ie8 ? 0 : (options.speed || 700);
    var currentIndex = 0;
    
    items.not(':first').hide();
    self.removeClass('ready');
    
    cycle = Core.Cycle(items.length, function(i, state) {
      state.lock();
      items.eq(currentIndex).fadeOut(t/2, function() {
        items.eq(i).fadeIn(t/2);
        currentIndex = i;
        state.unlock();
      });
    });
    
    if (options.random) cycle.random();
    
    if (options.pauseOnHover) cycle.hoverTarget(ul);
    
    if (options.autoplay) cycle.loop(options.timeout || 3500);
    
    if (options.touch) cycle.touchTarget(ul);
    
    nav.click(function() {
      var btn = this.__self || (this.__self = $(this));
      if (btn.hasClass('prev')) cycle.prev(); else cycle.next();
    });
    
  });
  
}

function initSkills() {
  
  $('.meteor-skills').each(function(i, elem) {
    var self = $(elem);
    var bars = self.find('ul li span.bar');
    var outerWidth = self.width();
    bars.each(function(i, elem) {
      var bar = $(elem);
      var pc = (bar.width()/outerWidth)*100;
      bar.width(0).animate({width: pc + '%'}, 1000, 'easeOutQuad');
    });
  });

}

function initAccordion() {
  
  $('.meteor-accordion').each(function(i, elem) {

    var activeIndex, busy = false;
    var self = $(elem).addClass('ready');
    var items = self.find('> .item');
    var titles = items.find('> h3.title').prepend('<span class="acc-box"></span>');
    var content = items.find('> .acc-content');

    var t = 250;
    var unlock = function() { busy = false; }

    items.each(function(i, elem) {
      var item = $(elem);
      if (item.hasClass('active')) {
        activeIndex = i;
      } else {
        content.eq(i).hide().slideUp(0);
      }
    });

    titles.each(function(i, elem) {
      
      var h3 = this.__self = $(elem);
      h3.data('index', i);
      
    }).click(function(e) {
      
      if (busy) return; else busy = true;
      
      var h3 = this.__self, index = h3.data('index');
      var target = content.eq(index);
      var current = content.eq(activeIndex);
      
      if (target.hasClass('active')) {
        e.preventDefault();
        unlock();
      } else {
        var item = items.eq(index);
        if (item.hasClass('active')) {
          e.preventDefault();
          unlock();
        } else {
          items.removeClass('active').eq(index).addClass('active');
          current.slideToggle(t);
          target.slideToggle(t, unlock);
          activeIndex = index;
        }
      }

    });
    
  });
  
}

function initTabs() {
  var tpl = Hogan.compile('<li data-index="{{{index}}}"{{#active}} class="active"{{/active}}>{{{title}}}</li>');
  $('.meteor-tabs').each(function(i, elem) {
    var self = $(elem).addClass('ready');
    var tabs = self.find('> .tab');
    var titles = tabs.find('> h3.tab-title');
    var head = $('<ul class="tabs-head clearfix"></ul>');
    tabs.each(function(i) {
      head.append(tpl.render({
        index: i,
        title: titles.eq(i).html(),
        active: $(this).hasClass('active')
      }));
    });
    head.find('> li:last-child').addClass('last-child');
    self.prepend(head);
    var headTitles = head.find('> li').click(function() {
      var tab = this.__self || (this.__self = $(this));
      var activeTab = tabs.eq(tab.data('index'));
      tab.addClass('active').siblings().removeClass('active');
      activeTab.addClass('active').siblings().removeClass('active');
    });
    Core.at('layout-change', function() {
      headTitles.height(headTitles.height());
    });
  });
}

function initToggle() {
  
  var t = 250;
  var easing = 'easeOutSine';
  
  $('.meteor-toggle').each(function(i, elem) {
    var self = $(elem);
    var busy = false;
    var div = self.find('> div.post-content');
    var unlock = function() { busy = false; }
    if (self.data('expanded')) self.addClass('expanded'); else div.hide();
    self.find('> h3').click(function() {
      if (busy) return; else busy = true;
      self[self.hasClass('expanded') ? 'removeClass' : 'addClass'].call(self, 'expanded');
      div.slideToggle(t, easing, unlock);
    });
  });

}

function initClients() {
  
  var t = 200;

  $('.meteor-clients').each(function(i, elem) {
    
    var self = $(elem);
    var rows = self.find('> .row');
    var links = self.find('.row > .item > a');
    
    if (self.hasClass('lightbox')) {
      links.attr('rel', Core.util.format("lightbox[%d]", Core.util.randInt()));
    }
    
    if (self.hasClass('opacity-effect')) {
      links.hover(function(e) {
        var self = this.__self || (this.__self = $(this));
        if (!self.data('opacity')) self.data('opacity', self.css('opacity'));
        switch (e.type) {
        case 'mouseenter':
          self.stop().animate({opacity: self.data('max-opacity') || 1}, t);
          break;
        case 'mouseleave':
          self.stop().animate({opacity: self.data('opacity')}, t);
          break;
        }
      });
    }

    if (!self.hasClass('scrollable')) {
      
      var loading = 2;
      
      // Automatically center items vertically
      var autoCenter = function() {
        if (loading) loading--;
        rows.each(function(i, elem) {
          var row = $(elem);
          var height = row.height();
          row.find('> li > *:first-child').each(function(i, elem) {
            var item = $(elem);
            var tm = Math.floor((height - item.outerHeight())/2.0);
            item.stop().animate({marginTop: tm}, loading ? 0 : 350, 'easeOutQuad');
          });
        });
      }
    
      // Auto resize on layout change
      Core.at('layout-change', function(layout) {
        if (layout === 'phone') {
          rows.find('> li > *:first-child').stop().animate({margin: 0}, 380, 'easeOutQuad');
        } else {
          autoCenter();
        }
      });
      
    }
    
  });

}

function initFooter() {

  var t = 600;
  var easing = 'easeOutSine';
  var body = Core.body;
  var win = Core.window;
  var widescreen = body.data('widescreen');
  var footer = $('body > footer[role=contentinfo]');
  var topBtn = $('<span class="top"></span>');
  var html = (Core.browser.opera || Core.browser.msie) ? $('html') : $('html, body');

  footer.prepend('<div class="bar-wrap"><span class="bar"></span></div>');
  footer.find('.bottom-bar').append(topBtn);

  topBtn.click(function() {
    html.stop().animate({scrollTop: 0}, t, easing);
    return false;
  });

  footer.hover(function(e) {
    switch (e.type) {
      case 'mouseenter':
      if (body.height() > win.height()) {
        var w = win.width();
        if ((w >= 480 && w <= 540) || (w >= 768 && w <= 826) || (w >= 980 && w <= 1036) || (widescreen && w >= 1200 && w <= 1256)) {
          topBtn.addClass('above');
        }
        footer.addClass('top-visible');
      }
      break;
      case 'mouseleave':
      footer.removeClass('top-visible');
      topBtn.removeClass('above');
      break;
    }
  });

}

})(jQuery, window.Core);
