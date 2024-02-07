<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\TwigHooks\Hookable\Renderer\CompositeHookableRenderer;
use Sylius\TwigHooks\Hookable\Renderer\HookableComponentRenderer;
use Sylius\TwigHooks\Hookable\Renderer\HookableRendererInterface;
use Sylius\TwigHooks\Hookable\Renderer\HookableTemplateRenderer;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set('twig_hooks.renderer.hookable', CompositeHookableRenderer::class)
        ->args([
            tagged_iterator('twig_hooks.hookable_renderer'),
        ])
        ->alias(HookableRendererInterface::class, 'twig_hooks.renderer.hookable')
        ->alias(sprintf('%s $compositeHookableRenderer', HookableRendererInterface::class), 'twig_hooks.renderer.hookable')
    ;

    $services->set('twig_hooks.renderer.hookable.component', HookableComponentRenderer::class)
        ->args([
            service('ux.twig_component.component_renderer'),
            service('twig_hooks.provider.component_data'),
            service('twig_hooks.provider.default_configuration'),
        ])
        ->tag('twig_hooks.hookable_renderer')
    ;

    $services->set('twig_hooks.renderer.hookable.template', HookableTemplateRenderer::class)
        ->args([
            service('twig'),
            service('twig_hooks.provider.default_data'),
            service('twig_hooks.provider.default_configuration'),
        ])
        ->tag('twig_hooks.hookable_renderer')
    ;
};
