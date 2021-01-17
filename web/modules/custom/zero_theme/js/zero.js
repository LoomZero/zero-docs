Drupal.zero = Drupal.zero || {};

Drupal.zero.api = {

  request: function(url, post = {}, response, error = null) {
    if (typeof url !== 'string') {
      url = url.toString();
    }

    var request = Drupal.ajax({url: url, submit: post});

    request.commands.apiResponse = response;

    request.commands.destroyObject = function () {
      Drupal.ajax.instances[this.instanceIndex] = null;
    };

    if (error) {
      request.options.error = error;
    }

    request.execute();
  },

  requestPromise: function(url, post = {}, error = null) {
    return new Promise(function (resolve) {
      Drupal.zero.api.request(url, post, function (ajax, response, status) {
        resolve({ajax, response, status});
      }, error);
    });
  },

};