<?php
/**
 * © 2016 Valiton GmbH
 */

namespace Drupal\xsl_process;

use ReflectionClass;

trait ClassRelativePathTrait {

  protected function getCurrentClassDirectory() {
    return dirname((new ReflectionClass(static::class))->getFileName());
  }

}