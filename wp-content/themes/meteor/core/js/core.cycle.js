
/* core.cycle.js */

(function($, Core) {
  
  Core.Cycle = function(len, handler) {
    return new Cycle(len, handler);
  }

  function Cycle(len, callback) {
    
    var intID, interval;
    var self = this;
    var busy = false;
    var random = false;
    var looping = false;
    var hoverTarget = false;
    var current = 0;
    
    if (callback instanceof Function) {
      var handler = function() {
        if (!hoverTarget) self.pause();
        callback.apply(this, arguments);
        if (!hoverTarget) self.resume();
      }
    } else {
      throw new Error("Invalid or missing callback function");
    }
    
    if (typeof len != 'number' || len <= 1) {
      len = 0;
      busy = true;
    }
    
    var state = {
      fromIndex: 0,
      locked: function() { return busy; },
      lock: function() { busy = true; },
      unlock: function() { busy = false; }
    }
    
    var randIndex = function() { var i;
      if (len) while ((i=Math.floor(Math.random()*len)) == current) continue;
      return i;
    }

    this.random = function(bool) {
      random = typeof bool == 'boolean' ? bool : true;
    }
    
    this.loop = function(ms) {
      if (ms && len && !intID) {
        looping = true;
        intID = setInterval(this.next, interval = ms);
      }
    }
    
    this.pause = function() {
      if (looping) {
        clearInterval(intID);
        intID = null;
      }
    }
    
    this.resume = function() {
      if (looping) {
        self.loop(interval);
      }
    }
    
    this.goto = function(index) {
      if (!busy && index >= 0 && index < len && index != current) {
        state.fromIndex = current;
        handler((current=index), state, (index > state.fromIndex) ? 'next' : 'prev');
      }
    }
    
    this.next = function() {
      if (busy) return;
      state.fromIndex = current;
      if (random) current = randIndex(); else if (++current >= len) current = 0;
      handler(current, state, 'next');
    }
    
    this.prev = function() {
      if (busy) return;
      state.fromIndex = current;
      if (random) current = randIndex(); else if (--current < 0) current = (len-1);
      handler(current, state, 'prev');
    }
    
    this.hoverTarget = function(elem) {
      if (len) {
        hoverTarget = true;
        elem.hover(self.pause, self.resume);
      }
    }
    
    this.touchTarget = function(elem) {
      elem.touchwipe({
        wipeLeft: self.next,
        wipeRight: self.prev
      });
    }
    
    this.keyboardTarget = function(elem) {
      var callback = function(e) {
        switch (e.keyCode) {
          case 37: self.prev(); break;
          case 39: self.next(); break;
        }
      }
      elem.hover(function(e) {
        switch (e.type) {
          case 'mouseenter':
            Core.body.bind('keyup', callback);
            break;
          case 'mouseleave':
            Core.body.unbind('keyup', callback);
            break;
        }
      });
    }

  }
  
})(jQuery, window.Core);