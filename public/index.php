<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    $env = is_string($context['APP_ENV']) ? $context['APP_ENV'] : 'dev';
    return new Kernel($env, (bool) $context['APP_DEBUG']);
};
