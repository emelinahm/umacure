
/* core.js */

Backbone.Events.emit = Backbone.Events.trigger;

if (typeof window.console === 'undefined') {
  window.console = {
    log: function() {},
    warn: function() {},
    error: function() {},
    trace: function() {}
  }
}

(function($, window) {

  var data = {};
  var debug = /\#debug$/.test(window.location + "");
  var events = _.extend({}, Backbone.Events);
  var ua = window.navigator.userAgent;
  var android = /Android/g.test(ua);
  var ios = !android && /(iPad|iPhone|iPod)/g.test(ua);
  var retina = (window.devicePixelRatio == 2);
  var html = $('html');
  var body = $('body');
  
  if (android) {
    body.addClass('android');
  } else if (ios) {
    body.addClass('ios');
  }
  
  if (retina) body.addClass('retina');
  
  var emittedEvents = {};
  
  window.Core = {
    
    debug: debug,
    
    debugLog: debug ? function(val) {
      console.log(val);
    } : function() {},
    
    emittedEvents: emittedEvents,
    
    body: body,
    
    html: html,
    
    window: $(window),
    
    ie: html.hasClass('ie'),
    
    ie8: html.hasClass('ie8'),
    
    ios: ios,
    
    android: android,
    
    retina: retina,
    
    log: (typeof window.console === 'object') ? console.log : function() {},
    
    on: function(evt, callback, context) {
      return events.on(evt, callback, context || this);
    },
    
    at: function(evt, callback, context) {
      var retval = events.on(evt, callback, context || this);
      if (evt in emittedEvents) callback.apply(context || this, emittedEvents[evt]);
      return retval;
    },

    once: function(evt, callback, context) {
      var cb = function() {
        callback.apply(this, arguments);
        events.off(evt, cb);
      }
      return events.on(evt, cb, context || this);
    },

    emit: function(evt) {
      var retval = events.emit.apply(events, arguments);
      emittedEvents[evt] = [].slice.call(arguments, 1);
      return retval;
    },
    
    path: function(path, version) {
      return this.root + '/' + (path || '').replace(/^\/+/, '') + ((version && path) ? '?ver=' + this.version : '');
    },
    
    nextTick: function(callback) {
      setTimeout(callback, 0);
    },
    
    initialize: function() {
      var self = this;
      $(document).ready(function() {
        Core.nextTick(function() {
          self.emit(':ready', body);
          self.emit('ready', body);
          self.emit('ready:', body);
        });
      });
    },
    
    EventEmitter: function EventEmitter() {
      return _.extend({}, Backbone.Events);
    }

  }
  
})(jQuery, window);
