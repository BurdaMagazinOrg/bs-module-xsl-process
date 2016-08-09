<?php
/**
 * © 2016 Valiton GmbH
 */

namespace Drupal\xsl_process;


use Drupal\Component\Plugin\PluginBase;
use XSLTProcessor;
use ReflectionClass;
use ReflectionMethod;

class XslProcessorBase extends PluginBase implements XslProcessorInterface {

  use ClassRelativePathTrait;

  public function getName() {
    return $this->pluginDefinition['name'];
  }

  /**
   * TODO find out whether another mechanism exists already
   *
   * @return string
   */
  public function getStylesheetUri() {
    // starts with protocol or DIRECTORY_SEPARTOR -> return unmodified path
    if (preg_match('#^(\w+://|' . preg_quote(DIRECTORY_SEPARATOR, '#') . ')#', $this->pluginDefinition['stylesheet_uri'])) {
      return $this->pluginDefinition['stylesheet_uri'];
    }
    // return path relative to directory of inheriting class
    return realpath($this->getCurrentClassDirectory() . DIRECTORY_SEPARATOR . $this->pluginDefinition['stylesheet_uri']);
  }

  /**
   * {@inheritdoc}
   */
  public function registerPhpFunctions(XSLTProcessor $xsltProcessor) {
    $class = new ReflectionClass($this->pluginDefinition['php_functions_provider']);
    $methods = [];
    foreach ($class->getMethods(ReflectionMethod::IS_STATIC | ReflectionMethod::IS_PUBLIC) as $method) {
      $methods[] = $class->getName() . '::' . $method->getName();
    }
    $xsltProcessor->registerPHPFunctions($methods);
  }

}