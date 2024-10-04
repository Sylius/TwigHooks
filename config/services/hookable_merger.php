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

use Sylius\TwigHooks\Hookable\Merger\HookableMerger;
use Sylius\TwigHooks\Hookable\Merger\HookableMergerInterface;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set('sylius_twig_hooks.merger.hookable', HookableMerger::class)
        ->alias(HookableMergerInterface::class, 'sylius_twig_hooks.merger.hookable')
    ;
};
