<?php
namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return App::config([
    'controllers' => [
        'resource' => [
            'path' => '../src/Controller/',
            'namespace' => 'App\Controller',
        ],
        'type' => 'attribute',
    ],
]);
