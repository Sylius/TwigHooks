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

use Sylius\TwigHooks\Hook\Renderer\HookRenderer;
use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set('sylius_twig_hooks.renderer.hook', HookRenderer::class)
        ->args([
            service('sylius_twig_hooks.registry.hookables'),
            service('sylius_twig_hooks.renderer.hookable'),
            service('sylius_twig_hooks.provider.default_context'),
            service('sylius_twig_hooks.provider.default_configuration'),
            service('sylius_twig_hooks.factory.hookable_metadata'),
        ])
        ->alias(HookRendererInterface::class, 'sylius_twig_hooks.renderer.hook')
    ;
};
