<?php

namespace Drupal\zero_theme\Ajax;

use Drupal\Core\Ajax\CommandInterface;

class ApiResponseAjaxCommand implements CommandInterface {

  private $data = NULL;
  private $code = NULL;

  public function __construct($data, int $code = 200) {
    $this->data = $data;
    $this->code = $code;
  }

  public function render() {
    return [
      'command' => 'apiResponse',
      'data' => $this->data,
      'code' => $this->code,
    ];
  }

}
