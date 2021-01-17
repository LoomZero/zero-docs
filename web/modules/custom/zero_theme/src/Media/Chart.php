<?php

namespace Drupal\zero_theme\Media;

use Drupal;
use Drupal\Component\Uuid\Php;
use Drupal\zero_entitywrapper\Content\ContentWrapper;
use Drupal\zero_theme\TwigExtension\ZeroFilter;

class Chart {

  public static $layout = [
    'red' => [
      'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
      'borderColor' => 'rgba(255, 99, 132, 1)',
    ],
    'blue' => [
      'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
      'borderColor' => 'rgba(54, 162, 235, 1)',
    ],
    'yellow' => [
      'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
      'borderColor' => 'rgba(255, 206, 86, 1)',
    ],
    'green' => [
      'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
      'borderColor' => 'rgba(75, 192, 192, 1)',
    ],
  ];

  private $data = [];

  public function __construct(string $title = '') {
    $this->data['labels'] = [$title];
  }

  public function addValue(string $label, int $data, array $info = [], string $layout = NULL, string $borderColor = NULL) {
    $item = [
      'label' => $label,
      'data' => [$data],
      'borderWidth' => 1,
      '__info' => $info,
    ];

    if ($borderColor !== NULL) {
      $item['backgroundColor'] = [$layout];
      $item['borderColor'] = [$borderColor];
    } else if ($layout !== NULL) {
      $item['backgroundColor'] = [self::$layout[$layout]['backgroundColor']];
      $item['borderColor'] = [self::$layout[$layout]['borderColor']];
    }

    $this->data['datasets'][] = $item;
    return $this;
  }

  public function render(ContentWrapper $proposal) {
    /** @var Php $uuid_generator */
    $uuid_generator = Drupal::service('uuid');
    $uuid = $uuid_generator->generate();

    return [
      '#theme' => 'zero_chart',
      '#uuid' => $uuid,
      '#attached' => [
        'drupalSettings' => [
          'zero' => [
            'chart_' . $uuid => [
              'data' => $this->data,
              'proposal' => $proposal->id(),
            ],
          ],
        ],
      ],
    ];
  }

}