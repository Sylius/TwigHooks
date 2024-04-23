<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\TwigHooks\Hookable\Merger\HookableMerger;
use Sylius\TwigHooks\Hookable\Merger\HookableMergerInterface;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set('twig_hooks.merger.hookable', HookableMerger::class)
        ->alias(HookableMergerInterface::class, 'twig_hooks.renderer.hookable')
    ;
};
