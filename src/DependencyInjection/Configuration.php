<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\DependencyInjection;

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
                    ->defaultValue([])
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
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('type')->defaultValue('template')->end()
                                ->scalarNode('target')->isRequired()->cannotBeEmpty()->end()
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
                                ->integerNode('priority')->defaultValue(0)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
