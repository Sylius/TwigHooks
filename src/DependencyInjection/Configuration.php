<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\DependencyInjection;

use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Hookable\HookableTemplate;
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
                        'template' => HookableTemplate::class,
                        'component' => HookableComponent::class,
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
                    ->useAttributeAsKey('_name')
                    ->arrayPrototype()
                        ->useAttributeAsKey('_name')
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
                                ->always(static function ($v) {
                                    $component = $v['component'] ?? null;
                                    $template = $v['template'] ?? null;

                                    if (null !== $component && null !== $template) {
                                        throw new \InvalidArgumentException('You cannot define both "component" and "template" at the same time.');
                                    }

                                    if (null === $component && [] !== $v['props']) {
                                        throw new \InvalidArgumentException('"Props" cannot be defined for non-component hookables.');
                                    }

                                    return $v;
                                })
                            ->end()
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('type')->defaultValue('template')->end()
                                ->scalarNode('target')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('component')->defaultNull()->end()
                                ->scalarNode('template')->defaultNull()->end()
                                ->arrayNode('context')
                                    ->defaultValue([])
                                    ->useAttributeAsKey('name')
                                    ->prototype('variable')->end()
                                ->end()
                                ->arrayNode('props')
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
