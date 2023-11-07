<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\TwigHooks\Hook\Renderer\Debug\HookDebugCommentRenderer;
use Sylius\TwigHooks\Hookable\Renderer\Debug\HookableDebugCommentRenderer;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $container, ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set('twig_hooks.renderer.hook.debug', HookDebugCommentRenderer::class)
        ->decorate('twig_hooks.renderer.hook')
        ->args([
            service('.inner'),
        ])
    ;

    $services->set('twig_hooks.renderer.hookable.debug', HookableDebugCommentRenderer::class)
        ->decorate('twig_hooks.renderer.hookable')
        ->args([
            service('.inner'),
        ])
    ;
};
