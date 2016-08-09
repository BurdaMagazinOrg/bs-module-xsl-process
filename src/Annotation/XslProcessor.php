<?php
/**
 * © 2016 Valiton GmbH
 */

namespace Drupal\xsl_process\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * @Annotation
 */
class XslProcessor extends Plugin {
  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The name of the processor.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $name;

  /**
   * The URI of the stylesheet
   *
   * @var string
   */
  public $stylesheet_uri;

  /**
   * The PHP functions provider class
   *
   * @var string
   */
  public $php_functions_provider;
}