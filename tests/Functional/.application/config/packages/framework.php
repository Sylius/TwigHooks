<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\HttpKernel\Kernel;

return static function (ContainerConfigurator $container): void {
    if (Kernel::MAJOR_VERSION >= 6) {
        $container->extension('framework', [
            'handle_all_throwables' => true,
        ]);
    }
};
