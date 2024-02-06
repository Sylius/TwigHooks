<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\DependencyInjection;

use Sylius\TwigHooks\Hookable\BaseHookable;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('twig_hooks');

        $rootNode = $treeBuilder->getRootNode();

        $this->addSupportedHookableTypesConfiguration($rootNode);
        $this->addHooksConfiguration($rootNode);

        return $treeBuilder;
    }

    private function addSupportedHookableTypesConfiguration(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('supported_hookable_types')
                    ->useAttributeAsKey('type')
                    ->defaultValue([
                        'template' => BaseHookable::class,
                        'component' => BaseHookable::class,
                    ])
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;
    }

    private function addHooksConfiguration(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('hooks')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->useAttributeAsKey('name')
                        ->arrayPrototype()
                            ->beforeNormalization()
                                ->always(function ($v) {
                                    $component = $v['component'] ?? null;
                                    $template = $v['template'] ?? null;

                                    if (null === $component && null === $template) {
                                        return $v;
                                    }

                                    $v['type'] = null !== $component ? 'component' : 'template';
                                    $v['target'] = $component ?? $template;

                                    return $v;
                                })
                            ->end()
                            ->validate()
                                ->ifTrue(function ($v) {
                                    $component = $v['component'] ?? null;
                                    $template = $v['template'] ?? null;

                                    return null !== $component && null !== $template;
                                })
                                ->thenInvalid('You cannot define both "component" and "template" at the same time.')
                            ->end()
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('type')->defaultValue('template')->end()
                                ->scalarNode('target')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('component')->defaultNull()->end()
                                ->scalarNode('template')->defaultNull()->end()
                                ->arrayNode('data')
                                    ->defaultValue([])
                                    ->useAttributeAsKey('name')
                                    ->prototype('variable')->end()
                                ->end()
                                ->arrayNode('configuration')
                                    ->defaultValue([])
                                    ->useAttributeAsKey('name')
                                    ->prototype('variable')->end()
                                ->end()
                                ->integerNode('priority')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
