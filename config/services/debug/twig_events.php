<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\TwigHooks\Hook\Renderer\Debug\HookDebugCommentRenderer;
use Sylius\TwigHooks\Hook\Renderer\Debug\HookProfilerRenderer;
use Sylius\TwigHooks\Hookable\Renderer\Debug\HookableDebugCommentRenderer;
use Sylius\TwigHooks\Hookable\Renderer\Debug\HookableProfilerRenderer;
use Sylius\TwigHooks\Profiler\HooksDataCollector;
use Sylius\TwigHooks\Profiler\Profile;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $container, ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set('twig_hooks.renderer.hook.debug_comment', HookDebugCommentRenderer::class)
        ->decorate('twig_hooks.renderer.hook', priority: 256)
        ->args([
            service('.inner'),
        ])
    ;

    $services->set('twig_hooks.renderer.hook.profiler', HookProfilerRenderer::class)
        ->decorate('twig_hooks.renderer.hook', priority: 512)
        ->args([
            service('.inner'),
            service('twig_hooks.profiler.profile')->nullOnInvalid(),
            service('debug.stopwatch')->nullOnInvalid(),
        ])
    ;

    $services->set('twig_hooks.renderer.hookable.debug_comment', HookableDebugCommentRenderer::class)
        ->decorate('twig_hooks.renderer.hookable', priority: 256)
        ->args([
            service('.inner'),
        ])
    ;

    $services->set('twig_hooks.renderer.hookable.profiler', HookableProfilerRenderer::class)
        ->decorate('twig_hooks.renderer.hookable', priority: 512)
        ->args([
            service('.inner'),
            service('twig_hooks.profiler.profile')->nullOnInvalid(),
            service('debug.stopwatch')->nullOnInvalid(),
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
