<?php
/**
 * © 2016 Valiton GmbH
 */

namespace Drupal\xsl_process;

use Drupal\image\Entity\ImageStyle;
use Drupal\user\Entity\User;
use Drupal\Component\Utility\UrlHelper;

abstract class DefaultPhpFunctionsProvider {

  public static function imageUrl($path, $style = 'default') {
    $style = ImageStyle::load($style);
    if ($style) {
      return $style->buildUrl($path);
    }
    return false;
  }

  public static function iriToUri($iri) {
      return $iri;
      $parts = UrlHelper::parse($iri);
      $parts['path'] = UrlHelper::encodePath($parts['path']);
      $parts['query'] = UrlHelper::buildQuery($parts['query']);
      return static::glueUrl($parts);
  }

  public static function fileUrl($file) {
    return file_create_url($file);
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

  public static function userDisplayName($uid) {
    if ($user = User::load($uid)) {
      return static::concat(' ', $user->field_forename->value, $user->field_surname->value);
    }
    return 'UNKNOWN';
  }

  protected static function glueUrl($parsed_url) {
    $scheme   = !empty($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
    $host     = !empty($parsed_url['host']) ? $parsed_url['host'] : '';
    $port     = !empty($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
    $user     = !empty($parsed_url['user']) ? $parsed_url['user'] : '';
    $pass     = !empty($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
    $pass     = ($user || $pass) ? "$pass@" : '';
    $path     = !empty($parsed_url['path']) ? $parsed_url['path'] : '';
    $query    = !empty($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
    $fragment = !empty($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
    return "$scheme$user$pass$host$port$path$query$fragment";
  }

  /**
   * Make incomplete URLs complete again.
   */
  public static function completeURLs($text) {
    $base_url = $GLOBALS['base_url'];
    $text = str_replace('src="/', 'src="' . $base_url . '/', $text);
    $text = str_replace('href="/', 'href="' . $base_url . '/', $text);
    return $text;
  }
}
