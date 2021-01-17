(function($) {
  'use strict';

  var script = {

    attach: function(context) {
      $('.chart:not(.chart--inited)').each(script.init);
    },

    init: function () {
      var item = $(this);
      var uuid = item.data('chart-uuid');
      var canvas = item.find('.chart__canvas');
      var chartWrapper = item.find('.chart__chart');
      var votesWrapper = item.find('.chart__votes');
      var content = item.find('.chart__content');
      var settings = drupalSettings.zero['chart_' + uuid];

      item.addClass('chart--inited');

      var context = canvas.get(0).getContext('2d');
      Chart.defaults.global.defaultFontSize = 20;
      var chart = new Chart(context, {
        type: 'bar',
        data: settings.data,
        options: {
          onClick: function (e) {
            var trigger = chart.getElementAtEvent(e)[0];
            var triggerData = settings.data.datasets[trigger._datasetIndex];

            Drupal.zero.api.requestPromise('/api/zero/proposal/chartdata', triggerData.__info).then(function (response) {
              content.html(response.response.data.output);
              content.find('.chart-vote__name').css('border-bottom', '3px solid ' + triggerData.backgroundColor[0]);
            });
          },
          onHover: function (e) {
            var trigger = chart.getElementAtEvent(e)[0];

            if (trigger) {
              item.addClass('chart--hover');
            } else {
              item.removeClass('chart--hover');
            }
          },
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true,
                precision: 0,
              }
            }]
          }
        }
      });

      $(window).resize(script.onResize.bind(script, chartWrapper, votesWrapper));
      script.onResize(chartWrapper, votesWrapper);

      script.request(content, settings, {id: settings.proposal});
    },

    onResize: function (chartWrapper, votesWrapper) {
      votesWrapper.css('height', chartWrapper.height());
    },

    request: function (content, settings, post) {
      Drupal.zero.api.requestPromise('/api/zero/proposal/chartdata', post).then(function (response) {
        content.html(response.response.data.output);

        for (var index in settings.data.datasets) {
          content
            .find('[data-proposal-option-key=' + settings.data.datasets[index].__info.key + '] .chart-vote__name')
            .css('border-bottom', '3px solid ' + settings.data.datasets[index].backgroundColor[0]);
        }
      });
    },

  };

  Drupal.behaviors.zeroChart = script;

})(jQuery);
