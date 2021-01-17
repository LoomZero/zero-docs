<?php

namespace Drupal\zero_theme\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ZeroFilter extends AbstractExtension {

  public static function code($string) {
    $string = preg_replace('/(`)(.*)(`)/U', '<span class="element__code">\2</span>', $string);
    if (is_string($string)) {
      $string = nl2br($string);
    }
    return $string;
  }

  public function getFilters() {
    return [
      new TwigFilter('code', [$this, 'codeFilter'], ['is_safe' => ['html']]),
    ];
  }

  public function getName() {
    return 'zero_theme.twig_extension';
  }

  public function codeFilter($string) {
    return ZeroFilter::code($string);
  }

}