<?php

$finder = (new PhpCsFixer\Finder())
    ->in([__DIR__ . '/src', __DIR__ . '/bin', __DIR__ . '/public'])
;

return (new PhpCsFixer\Config())
    //~ Rules
    ->setRules(
        [
            '@Symfony' => true,
        ],
    )

    //~ Format
    ->setFormat('txt')

    //~ Cache
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/build/.php-cs-fixer.cache')

    //~ Finder
    ->setFinder($finder)
;
