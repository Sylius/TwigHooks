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

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

//    $services->set('sylius_twig_hooks.live_component.hydration.data_bag', DataBagHydrationExtension::class)
//        ->tag('live_component.hydration_extension')
//    ;
};
