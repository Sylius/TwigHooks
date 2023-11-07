<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\TwigHooks\Provider\DefaultConfigurationProvider;
use Sylius\TwigHooks\Provider\DefaultDataProvider;
use Sylius\TwigHooks\Registry\HookablesRegistry;
use Sylius\TwigHooks\Twig\HooksExtension;

return static function (ContainerConfigurator $configurator): void {
    $configurator->import(__DIR__ . '/services/*.php');

    $services = $configurator->services();

    $services->set('twig_hooks.provider.default_data', DefaultDataProvider::class);

    $services->set('twig_hooks.provider.default_configuration', DefaultConfigurationProvider::class);

    $services->set('twig_hooks.registry.hookables', HookablesRegistry::class)
        ->args([
            tagged_iterator('twig_hooks.hookable'),
        ])
    ;

    $services->set(HooksExtension::class)
        ->args([
            service('twig_hooks.renderer.hook'),
        ])
        ->tag('twig.extension')
    ;
};
