<?php
/**
 * © 2016 Valiton GmbH
 */

namespace Drupal\xsl_process;

use DOMDocument;
use XSLTProcessor;

class StylesheetProcessor {

  protected $domDocument;

  protected $xsltProcessor;

  /**
   * StylesheetProcessor constructor.
   * @param \Drupal\xsl_process\XslProcessorInterface $xslPlugin
   */
  public function __construct(XslProcessorInterface $xslPlugin) {
    $this->domDocument = new DOMDocument();
    $this->xsltProcessor = new XSLTProcessor();
    $stylesheet = new DOMDocument();
    $stylesheet->load($xslPlugin->getStylesheetUri());
    $this->xsltProcessor->importStylesheet($stylesheet);
    $xslPlugin->registerPhpFunctions($this->xsltProcessor);
  }

  /**
   * @return \XSLTProcessor
   */
  public function getXsltProcessor() {
    return $this->xsltProcessor;
  }

  /**
   * @param string $xmlString
   * @return string
   */
  public function transform($xmlString) {
    $this->domDocument->loadXML($xmlString);
    return $this->xsltProcessor->transformToXml($this->domDocument);
  }

}