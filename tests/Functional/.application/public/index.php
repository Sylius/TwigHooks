<?php

use App\Kernel;

if (file_exists(dirname(__DIR__).'/vendor/autoload_runtime.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';
} elseif (file_exists(dirname(__DIR__, 4).'/vendor/autoload_runtime.php')) {
    require_once dirname(__DIR__, 4).'/vendor/autoload_runtime.php';
} else {
    require_once dirname(__DIR__, 6).'/vendor/autoload_runtime.php';
}

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
