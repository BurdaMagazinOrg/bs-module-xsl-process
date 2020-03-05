<?php
/**
 * © 2016 Valiton GmbH
 */

namespace Drupal\xsl_process;

use Drupal\image\Entity\ImageStyle;
use Drupal\user\Entity\User;
use Drupal\Component\Utility\UrlHelper;
use Drupal\media_entity_instagram\Plugin\MediaEntity\Type\Instagram;

abstract class DefaultPhpFunctionsProvider {

  public static function imageUrl($path, $style = 'default') {
    $style = ImageStyle::load($style);
    if ($style) {
      return $style->buildUrl($path);
    }
    return false;
  }

  public static function instagram($fieldValue) {
    $instagramEmbedFetcher = \Drupal::service('media_entity_instagram.instagram_embed_fetcher');
    foreach (Instagram::$validationRegexp as $pattern => $key) {
      if (preg_match($pattern, $fieldValue, $matches)) {
        return sprintf(
          '<iframe width="320" height="320" frameBorder="0" src="https://www.instagram.com/p/%s/embed" frameborder="0"></iframe>',
          $matches[$key]
        );
      }
    }
    return '';
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

  public static function dateRfc($date) {
    $timestamp = strtotime($date);
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

  public static function userDisplayName($uid, $anonymous_name = 'UNKNOWN') {
    if ($uid > 0 && $user = User::load($uid)) {
      return $user->get('field_forename')->value . ' ' . $user->get('field_surname')->value;
    }
    return $anonymous_name;
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
    $frontendBaseUrl = static::getFrontendBaseUrl();
    $sources = [
      'src="/',
      'href="/',
    ];
    $replacements = [
      'src="' . $frontendBaseUrl . '/',
      'href="' . $frontendBaseUrl . '/',
    ];
    str_replace($sources, $replacements, $text);
    return $text;
  }

  /**
   * Get frontend base URL from burdastyle_headless settings or global base_url.
   */
  public static function getFrontendBaseUrl() {
    global $base_url;
    $config = \Drupal::config('burdastyle_headless.settings');
    if ($frontendBaseUrl = $config->get('frontend_base_url')) {
      return $frontendBaseUrl;
    }
    return $base_url;
  }

  /**
   * Get complete URL for outgoing link.
   */
  public static function getCompleteUrl($url) {
    $base_url = static::getFrontendBaseUrl();
    // UrlHelper::parse does not give us the host.
    $base_components = parse_url($base_url);
    $url_components = parse_url($url);

    return $base_components['scheme'] . '://' . $base_components['host'] . $url_components['path'];
  }
}
