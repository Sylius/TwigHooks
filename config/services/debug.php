<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\TwigHooks\DependencyInjection\CompilerPass\UnregisterDebugServicesPass;
use Sylius\TwigHooks\Hook\Renderer\Debug\HookDebugCommentRenderer;
use Sylius\TwigHooks\Hookable\Renderer\Debug\HookableDebugCommentRenderer;
use Sylius\TwigHooks\Profiler\HooksDataCollector;
use Sylius\TwigHooks\Profiler\Profile;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $container, ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $services->defaults()->tag(UnregisterDebugServicesPass::DEBUG_TAG);

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

    $services->set('twig_hooks.profiler.profile', Profile::class);

    $services->set(HooksDataCollector::class)
        ->args([service('twig_hooks.profiler.profile')])
        ->tag('data_collector')
    ;

    $services->set(HooksDataCollector::class)
        ->args([service('twig_hooks.profiler.profile')])
        ->tag('data_collector', ['template' => '@TwigHooks/data_collector/hooks.html.twig', 'id' => 'sylius_twig_hooks'])
    ;
};
