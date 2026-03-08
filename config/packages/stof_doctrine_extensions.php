<?php
namespace Symfony\Component\DependencyInjection\Loader\Configurator;

// Read the documentation: https://symfony.com/doc/current/bundles/StofDoctrineExtensionsBundle/index.html
// See the official DoctrineExtensions documentation for more details: https://github.com/doctrine-extensions/DoctrineExtensions/tree/main/doc
return App::config([
    'stof_doctrine_extensions' => [
        'orm' => [
            'default' => [
                'timestampable' => true,
            ],
        ],
    ],
]);
