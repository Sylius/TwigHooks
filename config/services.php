<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\TwigHooks\Hook\NameGenerator\TemplateNameGenerator;
use Sylius\TwigHooks\Provider\ComponentDataProvider;
use Sylius\TwigHooks\Provider\DefaultConfigurationProvider;
use Sylius\TwigHooks\Provider\DefaultDataProvider;
use Sylius\TwigHooks\Registry\HookablesRegistry;
use Sylius\TwigHooks\Twig\HooksExtension;
use Sylius\TwigHooks\Twig\Runtime\HooksRuntime;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

return static function (ContainerConfigurator $configurator): void {
    $configurator->import(__DIR__ . '/services/*.php');

    $services = $configurator->services();

    $services->set('twig_hooks.provider.default_data', DefaultDataProvider::class);

    $services->set('twig_hooks.provider.component_data', ComponentDataProvider::class)
        ->args([
            inline_service(ExpressionLanguage::class),
        ])
    ;

    $services->set('twig_hooks.provider.default_configuration', DefaultConfigurationProvider::class);

    $services->set('twig_hooks.registry.hookables', HookablesRegistry::class)
        ->args([
            tagged_iterator('twig_hooks.hookable'),
        ])
    ;

    $services->set('twig_hooks.hook.name_generator.template', TemplateNameGenerator::class);

    $services->set(HooksExtension::class)
        ->args([
            service('twig_hooks.renderer.hook'),
            service('twig_hooks.registry.hookables'),
            service('twig_hooks.renderer.hookable'),
            service('twig_hooks.profiler.profile')->nullOnInvalid(),
            service('debug.stopwatch')->nullOnInvalid(),
        ])
        ->tag('twig.extension')
    ;

    $services->set(HooksRuntime::class)
        ->args([
            service('twig_hooks.renderer.hook'),
            service('twig_hooks.hook.name_generator.template'),
        ])
        ->tag('twig.runtime')
    ;
};
