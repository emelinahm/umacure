
/* client.search.js */

(function($, Core) {

Core.once('header-search-init', initHeaderSearch);

function initHeaderSearch() {
  
  var nav, align, body = Core.body;
  
  nav = body.find('ul#navigation');
  align = ((align = (nav.data('align'))) == 'center') ? 'right' : align;

  var header = $('header[role=banner]');
  var container = nav.parents('.container');
  var navItems = nav.find('> li:not(.search)');
  var navItemsLen = navItems.length - 1;
  var search = nav.find('> li.search');
  var input = search.find('input:text');
  var inputContainer = input.parents('p');
  var paddingSide = parseInt(search.css(align == 'right' ? 'paddingLeft' : 'paddingRight'), 10);
  var paddingTop = parseInt(search.css('paddingTop'), 10);
  var inputWidth = input.outerWidth();
  var logoImg = header.find('#logo img');
  
  // Get increment & offset
  var incr = (navItemsLen > 2) ? (1 / (navItemsLen - 1)) : 0;
  // var offset = 0.25;
  
  // Animation Vars
  var timeout, blurTimeout, t = 320;
  var easing = 'easeOutSine';
  var txt, text = getInputText(); 
  var open = false, busy = false;

  // Options
  var options = Core.util.parseOptions(search.data('options'));
  var autosearch = options.autosearch;
  var doAutoSearch = false;
  
  // Get input text
  function getInputText() {
    return $.trim(input.val());
  }

  // Fade navigation
  function fadeNav() {
    var offset = ((logoImg.width() + 20) / container.width()) + 0.1; // Width includes some margin
    var hiddenIndex = (align=='right') ? 0 : navItemsLen;
    navItems.eq(hiddenIndex).stop().animate({opacity: 0}, t, easing);
    for (var j,i=1; i < navItemsLen; i++) {
      j = (align=='right') ? i : navItemsLen - i;
      navItems.eq(j).stop().animate({opacity: incr*i - offset}, t, easing); 
    }
  }

  // Unfade navigation
  function unfadeNav() {
    for (var i=0; i < navItemsLen; i++) {
      navItems.eq(i).stop().animate({opacity: 1}, t, easing);
    } 
  }

  // Escape binding (ensure blur is triggered)
  function escapeBinding(e) {
    if (e.keyCode === 27) input.trigger('blur');
  }

  // Unlock busy
  function unlock() {
    busy = false;
  }

  // Input blur event
  input.blur(function(e) {
    blurTimeout = setTimeout(closeCallback, 100);
  });

  // Responsive events
  Core.on('tablet', function() {
    if (open) fadeNav();
  });

  Core.on('desktop', function() {
    if (open) unfadeNav();
  });
  
  // Animation parameters
  var whichPadding = (align == 'right') ? 'paddingLeft' : 'paddingRight';
  var whichDirection = (align == 'right') ? 'left' : 'right';

  var searchAnimOpen = {}, searchAnimClose = {};

  searchAnimOpen[whichPadding] = paddingSide;
  searchAnimClose[whichPadding] = paddingSide + inputWidth + 2*paddingTop;
  
  function hideInputContainer() {
    inputContainer.hide();
    open = false;
  }

  function closeCallback() {
    clearTimeout(timeout);
    search.removeClass('active');
    body.unbind('keyup', escapeBinding);
    search.stop().animate(searchAnimOpen, t, easing, unlock);
    inputContainer.stop().animate({opacity: 0}, t, easing, hideInputContainer);
    unfadeNav();
  }
  
  // Click handler
  search.click(function(e) {
    
    if (e.target.nodeName === 'LI') {
      
      if (busy) return; else busy = true;
      
      if (open) {
        
        // Closing Animation
        
        txt = getInputText();
        
        clearTimeout(blurTimeout);
        
        if (autosearch && txt && txt != text) {
          input.parents('form').submit();
        } else {
          closeCallback();
        }
        
      } else {
        
        // Opening Animation

        search.addClass('active');
        body.bind('keyup', escapeBinding);
        search.stop().animate(searchAnimClose, t, easing, unlock);
        
        timeout = setTimeout(function() {
          inputContainer.stop().css(whichDirection, 0).show().animate({opacity: 1}, t);
        }, 0.5*t);
        
        if (container.width() < 940 && incr) fadeNav();
        
      }
      
      open = !open; // Toggle state

    }
  }); 

}

})(jQuery, window.Core);
