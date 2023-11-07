<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\TwigHooks\Hook\Renderer\HookRenderer;
use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set('twig_hooks.renderer.hook', HookRenderer::class)
        ->args([
            service('twig_hooks.registry.hookables'),
            service('twig_hooks.renderer.hookable'),
        ])
        ->alias(HookRendererInterface::class, 'twig_hooks.renderer.hook')
    ;
};
