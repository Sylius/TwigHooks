<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\TwigHooks\Hook\Normalizer\Name\CompositeNameNormalizer;
use Sylius\TwigHooks\Hook\Normalizer\Name\NameNormalizerInterface;
use Sylius\TwigHooks\Hook\Normalizer\Prefix\CompositePrefixNormalizer;
use Sylius\TwigHooks\Hook\Normalizer\Prefix\PrefixNormalizerInterface;
use Sylius\TwigHooks\Hook\Normalizer\Prefix\RemoveSectionPartNormalizer;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadataFactory;
use Sylius\TwigHooks\Provider\ComponentPropsProvider;
use Sylius\TwigHooks\Provider\DefaultConfigurationProvider;
use Sylius\TwigHooks\Provider\DefaultContextProvider;
use Sylius\TwigHooks\Registry\HookablesRegistry;
use Sylius\TwigHooks\Twig\HooksExtension;
use Sylius\TwigHooks\Twig\Runtime\HooksRuntime;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

return static function (ContainerConfigurator $configurator): void {
    $configurator->import(__DIR__ . '/services/*.php');

    $services = $configurator->services();

    $services->set('twig_hooks.provider.default_context', DefaultContextProvider::class);

    $services->set('twig_hooks.provider.component_props', ComponentPropsProvider::class)
        ->args([
            inline_service(ExpressionLanguage::class),
        ])
    ;

    $services->set('twig_hooks.provider.default_configuration', DefaultConfigurationProvider::class);

    $services->set('twig_hooks.registry.hookables', HookablesRegistry::class)
        ->args([
            tagged_iterator('twig_hooks.hookable'),
            service('twig_hooks.merger.hookable'),
        ])
    ;

    $services
        ->set('twig_hooks.hook.normalizer.name.composite', CompositeNameNormalizer::class)
        ->args([
            tagged_iterator('twig_hooks.hook.name_normalizer'),
        ])
        ->alias(NameNormalizerInterface::class, 'twig_hooks.hook.normalizer.name.composite')
    ;

    $services
        ->set('twig_hooks.hook.normalizer.prefix.composite', CompositePrefixNormalizer::class)
        ->args([
            tagged_iterator('twig_hooks.hook.prefix_normalizer'),
        ])
        ->alias(PrefixNormalizerInterface::class, 'twig_hooks.hook.normalizer.prefix.composite')
    ;

    $services
        ->set('twig_hooks.hook.normalizer.prefix.remove_section_part', RemoveSectionPartNormalizer::class)
        ->args([
            param('twig_hooks.hook_name_section_separator'),
        ])
        ->tag('twig_hooks.hook.prefix_normalizer')
        ->alias(
            sprintf('%s $removeSectionPartNormalizer', PrefixNormalizerInterface::class),
            'twig_hooks.hook.normalizer.prefix.remove_section_part',
        )
    ;

    $services->set('twig_hooks.factory.hookable_metadata', HookableMetadataFactory::class)
        ->args([
            service('twig_hooks.hook.normalizer.prefix.composite'),
        ])
    ;

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
            service('twig_hooks.hook.normalizer.name.composite'),
            service('twig_hooks.hook.normalizer.prefix.composite'),
            param('twig_hooks.enable_autoprefixing'),
        ])
        ->tag('twig.runtime')
    ;
};
