<?php
// This is global bootstrap for autoloading

// bootstrap

include_once __DIR__ . '/Extensions/popsugar.php';

// include all class file from common_library directory
\Codeception\Util\Autoload::registerSuffix('Class', __DIR__ . '/common_library');

\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages');
