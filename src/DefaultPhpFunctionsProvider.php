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

  /**
   * Concatenates non-empty strings.
   *
   * Needs to use variadic signature with splat operator as XSL cannot use arrays
   * as arguments to php functions.
   *
   * @param string $delim
   * @param array[string] ...$parts
   * @return string
   */
  public static function concat($delim, ...$parts) {
    return join($delim, array_filter($parts));
  }

}