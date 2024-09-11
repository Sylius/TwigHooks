<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\HttpKernel\Kernel;

return static function (ContainerConfigurator $container): void {
    if (Kernel::MAJOR_VERSION >= 6) {
        $container->extension('framework', [
            'handle_all_throwables' => true,
        ]);
    }
};
