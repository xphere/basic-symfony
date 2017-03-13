<?php

use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/** @var ClassLoader $loader */
$loader = require __DIR__ . '/lib/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
