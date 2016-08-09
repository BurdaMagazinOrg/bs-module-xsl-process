<?php
/**
 * © 2016 Valiton GmbH
 */

namespace Drupal\xsl_process;

use Drupal\Component\Plugin\PluginInspectionInterface;
use XSLTProcessor;

interface XslProcessorInterface extends PluginInspectionInterface {

  /**
   * Returns the name of this processor
   *
   * @return string
   */
  public function getName();

  /**
   * Return the URI to the stylesheet.
   *
   * Must be compatible with DOMDocument::load() as it is passed through.
   *
   * @return string
   */
  public function getStylesheetUri();


  /**
   * Return the fq class name of a class containing the PHP functions available
   * in the XSL stylesheet.
   *
   * Must extend BasePhpFunctionsProvider
   *
   * @param \XSLTProcessor $xsltProcessor
   * @return string
   */
  public function registerPhpFunctions(XSLTProcessor $xsltProcessor);

}