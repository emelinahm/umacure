
/* IE Fixes */

(function($, Core) {
  
var ie8 = Core.ie8;
  
Core.once(':ready', function() {
  initPlaceholders();
  if (ie8) {
    fixComments();
    addWidgetStyles();
    fixThumbAsidePosts();
  }
});

function fixThumbAsidePosts() {
  $('.meteor-post.thumb-aside > .post-img > a img').each(function(i, elem) {
    var img = $(elem);
    var link = img.parents('a');
    Core.at('layout-change', function(layout, variant) {
      switch (layout) {
        case 'desktop':
        case 'tablet':
          link.width(img.attr('width'));
          break;
        default:
          link.removeAttr('style');
          break;
      }
    });
  });
}

function addWidgetStyles() {
  $('.widget > h3.title:first-child + ul:last-child,\
.widget > h3.title:first-child + ul.last-child,\
.widget_nav_menu ul.menu').addClass('link_list');
}

function fixComments() {
  var comments = $('.meteor-post > .meteor-comments > ol.commentlist');
  if (comments.length) {
    comments.find('li.comment .comment-meta .comment-author').each(function(i, elem) {
      var meta = $(elem);
      meta.html(meta.children());
    });
  }
}

function initPlaceholders() {
  $('form input[placeholder]').each(function(i, elem) {
    var self = $(elem);
    var placeholder = $.trim(self.attr('placeholder') || '');
    if (placeholder) {
      self.val(placeholder);
      self.focus(function(e) {
        if ($.trim(self.val()) === placeholder) self.val('');
      }).blur(function(e) {
        if ($.trim(self.val()) == '') self.val(placeholder);
      });
    }
  });
}
  
})(jQuery, window.Core);