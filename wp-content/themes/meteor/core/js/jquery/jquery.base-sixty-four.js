/*
 * Original code (c) 2010 Nick Galbreath
 * http://code.google.com/p/stringencoders/source/browse/#svn/trunk/javascript
 * jQuery port (c) 2010 Carlo Zottmann
 * http://github.com/carlo/jquery-base64
 * MIT Licensed
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
*/
jQuery.base64=function(a){function e(a,b){var d=c.indexOf(a.charAt(b));if(d===-1)throw"Cannot decode base64";return d}function f(a){var c=0,d,f,g=a.length,h=[];a=String(a);if(g===0)return a;if(g%4!==0)throw"Cannot decode base64";a.charAt(g-1)===b&&(c=1,a.charAt(g-2)===b&&(c=2),g-=4);for(d=0;d<g;d+=4)f=e(a,d)<<18|e(a,d+1)<<12|e(a,d+2)<<6|e(a,d+3),h.push(String.fromCharCode(f>>16,f>>8&255,f&255));switch(c){case 1:f=e(a,d)<<18|e(a,d+1)<<12|e(a,d+2)<<6,h.push(String.fromCharCode(f>>16,f>>8&255));break;case 2:f=e(a,d)<<18|e(a,d+1)<<12,h.push(String.fromCharCode(f>>16))}return h.join("")}function g(a,b){var c=a.charCodeAt(b);if(c>255)throw"INVALID_CHARACTER_ERR: DOM Exception 5";return c}function h(a){if(arguments.length!==1)throw"SyntaxError: exactly one argument required";a=String(a);var d,e,f=[],h=a.length-a.length%3;if(a.length===0)return a;for(d=0;d<h;d+=3)e=g(a,d)<<16|g(a,d+1)<<8|g(a,d+2),f.push(c.charAt(e>>18)),f.push(c.charAt(e>>12&63)),f.push(c.charAt(e>>6&63)),f.push(c.charAt(e&63));switch(a.length-h){case 1:e=g(a,d)<<16,f.push(c.charAt(e>>18)+c.charAt(e>>12&63)+b+b);break;case 2:e=g(a,d)<<16|g(a,d+1)<<8,f.push(c.charAt(e>>18)+c.charAt(e>>12&63)+c.charAt(e>>6&63)+b)}return f.join("")}var b="=",c="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",d="1.0";return{decode:f,encode:h,VERSION:d}}(jQuery);
