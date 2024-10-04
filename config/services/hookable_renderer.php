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

use Sylius\TwigHooks\Hookable\Renderer\CompositeHookableRenderer;
use Sylius\TwigHooks\Hookable\Renderer\HookableComponentRenderer;
use Sylius\TwigHooks\Hookable\Renderer\HookableRendererInterface;
use Sylius\TwigHooks\Hookable\Renderer\HookableTemplateRenderer;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set('sylius_twig_hooks.renderer.hookable', CompositeHookableRenderer::class)
        ->args([
            tagged_iterator('sylius_twig_hooks.hookable_renderer'),
        ])
        ->alias(HookableRendererInterface::class, 'sylius_twig_hooks.renderer.hookable')
        ->alias(sprintf('%s $compositeHookableRenderer', HookableRendererInterface::class), 'sylius_twig_hooks.renderer.hookable')
    ;

    $services->set('sylius_twig_hooks.renderer.hookable.component', HookableComponentRenderer::class)
        ->args([
            service('sylius_twig_hooks.provider.component_props'),
            service('ux.twig_component.component_renderer'),
        ])
        ->tag('sylius_twig_hooks.hookable_renderer')
    ;

    $services->set('sylius_twig_hooks.renderer.hookable.template', HookableTemplateRenderer::class)
        ->args([
            service('twig'),
        ])
        ->tag('sylius_twig_hooks.hookable_renderer')
    ;
};
