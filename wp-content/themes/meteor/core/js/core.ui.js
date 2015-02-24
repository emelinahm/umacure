
/* core.ui.js */

(function($, Core) {

Core.ui = {
  
  readyUp: function(selector) {
    $(selector).addClass('ready');
  },
  
  lastChild: function(selector) {
    $(selector).find('> *:last-child').addClass('last-child');
  },
  
  createMobileNav: function(nav, attrs) {
    
    var html;
    var select = $("<select></select>");
    var menuItems = [];
    
    (nav = nav.clone()).find('li.search').remove();
    
    if (!Core.browser.msie && !Core.android) {
      html = nav.html()
      .replace(/(alt|title|class|id)(\s+)?\=(\s+)?(\'|\")(.*?)(\'|\")/g, '')
      .replace(/href/g, 'value')
      .replace(/<(\/)?ul([ ]*)?>/g,'<$1optgroup>')
      .replace(/<li(\s)?>(\s+)?<a|<a/g, '<option')
      .replace(/(<\/a\>)?<\/li>/g, '</option>')
      .replace(/<\/a>/g,'')
      .replace(/[ ]+/g,' ');
    } else {
      // Don't add support for <optgroup> on old browsers
      var link, links = nav.find('a');
      links.each(function(i) {
        link = $(links[i]);
        html += Core.util.format('<option value="%s">%s</option>', link.attr('href'), link.html());
      });
    }
    
    select.html(html).change(function() { 
      window.location = $(this).attr('value');
    });

    var active = nav.find('li.current-menu-item > a, li.current_page_item > a').attr('href');
    
    var optgroups = select.find('optgroup');

    nav.find('ul').each(function(i, elem) {
      var anchor = $(elem).prev();
      optgroups.eq(i).attr('label', '    ↳ ' + anchor.text() + ' ⌄');
    });

    select.find('option').each(function() {
      var self = $(this);
      if ( self.attr('value') == active ) {
        self.attr('selected', 'true');
        return false;
      }
    });

    if (attrs) select.attr(attrs);

    return select;

  },
  
  addContent: function() {
    var overlay = '<span class="overlay"></span>';
    $('.post-content .wp-caption a').addClass('framed-image').append(overlay);
    $('.meteor-mini-posts > li > a.post-img').append(overlay);
    $('table.zebra tbody tr:even').addClass('odd');
    $('table.odd-stripes:not(.zebra) tbody tr:odd').addClass('odd');
    $('table.even-stripes:not(.zebra) tbody tr:even, table.zebra tbody tr.even').addClass('odd');
  },
  
  removeLastChildMargins: function(selector) {
    selector = selector.split(',');
    for (var i=0,len=selector.length; i < len; i++) {
      selector[i] = $.trim(selector[i]) + ' > *:last-child';
    }
    $('.widget > *:last-child, ' + selector.join(', ')).css('marginBottom', 0);
  },
  
  initTooltips: function(selector) {
    $(selector).each(function(i, elem) {
      var self = $(elem);
      var options = Core.util.parseOptions(self.data('tooltip-options'));
      self.find('[title]').tooltip(options);
    });
  },
  
  initRows: function(selector) {
    return $(selector).each(function(i, elem) {
      var self = $(elem);
      self.find('> .row:last').addClass('last-child');
      self.find('> .row > .item:nth-child(even)').addClass('nth-child-even');
    });
  },
  
  initFrames: function(selectors, absolute) {

    var frameLink = Hogan.compile('<a class="{{{where}}} {{{type}}}"{{#href}} href="{{{href}}}"{{/href}}></a>');
    
    $.each(selectors.split(','), function(i, selector) {
      
      $(selector).each(function(i, elem) {
      
        var self = elem.__self = $(elem);
        var options = self.data();
        var frames = self.find('.item > div.frame');
        
        // Trigger hover effect on IOS devices
        if (Core.ios) frames.hover(function() {
          this.__self.toggleClass('hover-effect');
        });
        
        // Set gallery ID
        var galleryID = (options.gallery) ? Core.util.randInt() : null;
      
        frames.each(function(i, elem) {
          
          var href, zoomBtn, linkBtn;
          var self = elem.__self = $(elem);
          
          if (self.data('initialized')) return; else self.data('initialized', true);

          var link = self.find('> a');
          var image = link.find('img');
          var classes = ['above', 'below'];
          var overlay = $('<span class="overlay"></span>');
          
          // self.append(overlay);
          
          var btnDiv = $('<div class="btns"></div>').appendTo(self).append(overlay);

          if (options.lightbox || options.clickBehavior == 'lightbox') {

            zoomBtn = $(frameLink.render({
              where: options.lightbox ? classes.pop() : '',
              type: 'zoom'
            }));
            
            zoomBtn.click(function() {
              link.trigger('click');
            });
            
            if (options.gallery) {
              link.attr('rel', "lightbox[" + galleryID + "]");
            } else {
              link.attr('rel', "lightbox");
            }
            
            if (options.lightbox) {
              zoomBtn.appendTo(btnDiv);
            } else {
              var imgSrc = image.attr('src').replace(/(.+)-\d+x\d+\.([a-z0-9]{3,})$/i, '$1.$2');
              link.attr('href', imgSrc);
            }
            
          }

          if (options.permalink && (href = self.data('permalink'))) {

            linkBtn = frameLink.render({
              where: classes.pop(),
              type: 'permalink',
              href: href
            });

            btnDiv.append(linkBtn);

          }
          
          switch (options.clickBehavior) {
            case 'permalink':
              var permalink = self.data('permalink');
              if (permalink) overlay.wrap('<a class="overlay-link" href="'+ self.data('permalink') +'"></a>');
              break;
            case 'lightbox':
              overlay.addClass('hoverable').click(function() {
                link.trigger('click');
              });
              break;
          }
          
        });
      
      });

    });
    
  }

}
  
})(jQuery, window.Core);