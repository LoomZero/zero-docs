<?php

namespace Drupal\zero_theme\Controller;

use Drupal;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\RendererInterface;
use Drupal\zero_theme\Ajax\ApiResponseAjaxCommand;
use Drupal\zero_theme\Exception\APIException;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class APIControllerBase extends ControllerBase {

  /**
   * @var RendererInterface
   */
  private $renderer = NULL;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): APIControllerBase {
    $instance = parent::create($container);
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  protected function renderer(): RendererInterface {
    return $this->renderer;
  }

  protected function response($data = [], int $code = 200): AjaxResponse {
    $ajax = new AjaxResponse();
    $ajax->addCommand(new ApiResponseAjaxCommand($data, $code));
    return $ajax;
  }

  /**
   * @param $definition
   *   Allowed formats: <pre>
   *   [
   *     'required_field', // exception without
   *     'optional_field_any' => 'fallback_any',
   *     'optional_field_cast_number' => 10,
   *   ]</pre>
   *
   * @return array
   */
  protected function params($definition): array {
    $params = [];
    foreach ($definition as $name => $fallback) {
      $key = $name;
      if (is_numeric($name)) {
        $key = $fallback;
        $params[$fallback] = Drupal::request()->get($fallback, NULL);
        if ($params[$fallback] === NULL) {
          throw new APIException('The parameter "' . $fallback . '" is required!');
        }
      } else {
        $params[$name] = Drupal::request()->get($name, $fallback);
      }

      if (is_numeric($params[$key])) {
        $params[$key] = (int)$params[$key];
      }
    }
    return $params;
  }

  protected function error(APIException $error): AjaxResponse {
    return $this->response([
      'error' => $error->getMessage(),
    ], 400);
  }

  protected function render(array $render_array = []): MarkupInterface {
    $options = Drupal::request()->get('options', []);
    if (!empty($options['wrapper'])) {
      $render_array = [
        '#theme' => $options['wrapper'],
        '#content' => $render_array,
      ];
    }
    return $this->renderer()->render($render_array);
  }

}
