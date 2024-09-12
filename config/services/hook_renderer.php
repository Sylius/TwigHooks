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

    $services->set('twig_hooks.renderer.hook', HookRenderer::class)
        ->args([
            service('twig_hooks.registry.hookables'),
            service('twig_hooks.renderer.hookable'),
            service('twig_hooks.provider.default_context'),
            service('twig_hooks.provider.default_configuration'),
            service('twig_hooks.factory.hookable_metadata'),
        ])
        ->alias(HookRendererInterface::class, 'twig_hooks.renderer.hook')
    ;
};
