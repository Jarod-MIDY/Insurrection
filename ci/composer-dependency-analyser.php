<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

function getConfigFiles(): array
{
    $files = [];
    $paths = [__DIR__ . '/../config/*'];

    while ($paths !== []) {
        $path = array_shift($paths);
        foreach (glob($path) as $filePathname) {
            if (is_dir($filePathname)) {
                $paths[] = $filePathname . '/*';
            } elseif (str_ends_with($filePathname, '.yaml')) {
                $files[] = $filePathname;
            }
        }
    }

    sort($files);
    return $files;
}

$classNameRegex = '[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*';
$pattern        = "`$classNameRegex(?:\\\\$classNameRegex)+`";
$classes        = [];
foreach (getConfigFiles() as $file) {
    $file = realpath($file);
    $dicFileContents = \file_get_contents($file);
    \preg_match_all($pattern, $dicFileContents, $matches);
    $classes = array_merge($classes, $matches[0]);
}

return $config
    ->addPathToScan(__DIR__ . '/../bin/console', isDev: false)
    ->addPathToScan(__DIR__ . '/../config/bundles.php', isDev: false)
    ->addPathToScan(__DIR__ . '/../config/preload.php', isDev: false)
    ->addPathToScan(__DIR__ . '/../public/index.php', isDev: false)
    ->addPathToScan(__DIR__ . '/../src', isDev: false)
    ->addPathToScan(__DIR__ . '/../tests', isDev: true)
    ->addForceUsedSymbols($classes)
    ->ignoreErrorsOnExtensions(['ext-ctype', 'ext-iconv'], [ErrorType::UNUSED_DEPENDENCY]) // Required, but references are only in autoloader
    ->ignoreErrorsOnPackages(['symfony/asset', 'symfony/asset-mapper', 'symfony/console', 'symfony/dotenv', 'symfony/flex', 'symfony/runtime', 'symfony/yaml', 'twig/twig'], [ErrorType::UNUSED_DEPENDENCY]) // Required, but references are only in autoloader
;
