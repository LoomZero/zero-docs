(function($) {
  'use strict';

  var script = {

    attach: function(context) {
      $('.proposal:not(.proposal--inited)').each(script.init);
    },

    init: function () {
      var context = {
        form: $(this),
      };
      context.name = context.form.find('.proposal__name');
      context.submit = context.form.find('.button[data-proposal-type="submit"]');
      context.comment = context.form.find('.proposal__comment');
      context.steps = context.form.find('.proposal__step');
      context.track = context.form.find('.proposal__track');
      context.result = context.form.find('.proposal__step-title-result');
      context.values = {
        id: context.form.data('proposal-id'),
      };
      context.step = 0;

      context.track.css('width', 100 * context.steps.length + '%');

      context.name.keyup(function (e) {
        if (e.keyCode === 13) {
          context.values.name = context.name.val();
          script.next(context);
        }
      });
      context.form.find('.button[data-proposal-type="option"]').click(function () {
        context.values.option = $(this).data('proposal-value');
        script.next(context);
      });
      context.comment.keyup(function () {
        if (context.comment.val()) {
          context.submit.html('Submit comment');
        } else {
          context.submit.html('Skip comment');
        }
      });
      context.submit.click(function () {
        context.values.comment = context.comment.val();
        script.submit(context);
      });
    },

    next: function (context) {
      context.step++;
      context.track.css('transform',  'translateX(-' + (100 / context.steps.length * context.step) +  '%)');
    },

    submit: function (context) {
      Drupal.zero.api.requestPromise('/api/zero/proposal', context.values).then(function (response) {
        context.result.html(response.response.data.text);
        script.next(context);
      });
    },

  };

  Drupal.behaviors.zeroProposal = script;

})(jQuery);
