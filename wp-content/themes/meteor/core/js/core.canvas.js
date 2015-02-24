
/* core.canvas.js */

(function($, Core) {
  
if (Core.ie8) return;
  
var pixelRatio = window.devicePixelRatio || 1;

Core.once('meteor-shapes-init', initShapes);

var shapes = {

  hex: function($canvas, bg) {
    var canvas = $canvas.get(0);
    var c = canvas.getContext('2d');
    var w = canvas.width * pixelRatio;
    var h = canvas.height * pixelRatio;
    var hs = w/2.0;
    var hm = (w - hs)/2.0;
    var vm = h/2.0;
  
    $canvas.attr('width', w);
    $canvas.attr('height', h);
    $canvas.css('width', w/pixelRatio);
    $canvas.css('height', h/pixelRatio);
  
    c.fillStyle = bg;
    c.beginPath();
    c.moveTo(hm, 0);
    c.lineTo(hm + hs, 0);
    c.lineTo(w, vm);
    c.lineTo(hm + hs, h);
    c.lineTo(hm, h);
    c.lineTo(0, vm);
    c.fill();

  }
}

function initShapes(targets) {
  targets.each(function(i, canvas) {
    var c = $(canvas), shape = c.data('shape'), bg = c.data('fill-color');
    shapes[shape](c, bg);
  });
}

})(jQuery, window.Core);