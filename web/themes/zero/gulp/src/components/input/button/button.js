(function($) {
  'use strict';

  var script = {

    attach: function(context) {
      $('.button:not(.button--inited)').each(script.init);
    },

    init: function () {
      var button = $(this);

      button
        .on('mousedown', function () {
          button.addClass('button--down');
        })
        .on('mouseup', function () {
          button.removeClass('button--down');
        })
        .on('mouseleave', function () {
          button.removeClass('button--down');
        })
        .addClass('button--inited');
    },


  };

  Drupal.behaviors.zeroButton = script;

})(jQuery);
