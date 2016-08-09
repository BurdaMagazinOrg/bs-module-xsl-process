<?php
/**
 * © 2016 Valiton GmbH
 */

namespace Drupal\xsl_process\Plugin\xsl_processor;


use Drupal\xsl_process\XslProcessorBase;

/**
 * Provides a test processor that processes to identity.
 *
 * @XslProcessor(
 *   id = "identity",
 *   name = @Translation("Identity Processor"),
 *   stylesheet_uri = "identity.xsl",
 *   php_functions_provider = "\Drupal\xsl_process\DefaultPhpFunctionsProvider"
 * )
 */
class Identity extends XslProcessorBase {

}