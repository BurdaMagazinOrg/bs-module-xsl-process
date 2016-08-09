<?php
/**
 * © 2016 Valiton GmbH
 */

namespace Drupal\xsl_process;

use Drupal\image\Entity\ImageStyle;

class DefaultPhpFunctionsProvider {

  public static function imageUrl($path, $style = 'default') {
    $style = ImageStyle::load($style);
    if ($style) {
      return $style->buildUrl($path);
    }
    return false;
  }

  public static function dateRfc($timestamp) {
    return date('r', $timestamp);
  }

  public static function dateIso($timestamp) {
    return date('c', $timestamp);
  }

}