/**
 * Copyright (c) 2011-2014 Felix Gnass
 * Licensed under the MIT license
 */

/*

Basic Usage:
============

jQuery('#el').spin(); // Creates a default Spinner using the text color of #el.
jQuery('#el').spin({ ... }); // Creates a Spinner using the provided options.

jQuery('#el').spin(false); // Stops and removes the spinner.

Using Presets:
==============

jQuery('#el').spin('small'); // Creates a 'small' Spinner using the text color of #el.
jQuery('#el').spin('large', '#fff'); // Creates a 'large' white Spinner.

Adding a custom preset:
=======================

jQuery.fn.spin.presets.flower = {
  lines: 9
  length: 10
  width: 20
  radius: 0
}

jQuery('#el').spin('flower', 'red');

*/

(function(factory) {

  if (typeof exports == 'object') {
    // CommonJS
    factory(require('jquery'), require('spin'))
  }
  else if (typeof define == 'function' && define.amd) {
    // AMD, register as anonymous module
    define(['jquery', 'spin'], factory)
  }
  else {
    // Browser globals
    if (!window.Spinner) throw new Error('Spin.js not present')
    factory(window.jQuery, window.Spinner)
  }

}(function(jQuery, Spinner) {

  jQuery.fn.spin = function(opts, color) {

    return this.each(function() {
      var jQuerythis = jQuery(this),
        data = jQuerythis.data();

      if (data.spinner) {
        data.spinner.stop();
        delete data.spinner;
      }
      if (opts !== false) {
        opts = jQuery.extend(
          { color: color || jQuerythis.css('color') },
          jQuery.fn.spin.presets[opts] || opts
        )
        data.spinner = new Spinner(opts).spin(this)
      }
    })
  }

  jQuery.fn.spin.presets = {
    tiny: { lines: 8, length: 2, width: 2, radius: 3 },
    small: { lines: 8, length: 4, width: 3, radius: 5 },
    large: { lines: 10, length: 8, width: 4, radius: 8 }
  }

}));