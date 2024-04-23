<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\DependencyInjection;

use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Hookable\HookableTemplate;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class TwigHooksExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(dirname(__DIR__, 2) . '/config'));
        $loader->load('services.php');

        if ($container->hasParameter('kernel.debug') && $container->getParameter('kernel.debug')) {
            $loader->load('services/debug/twig_events.php');
        }

        $configuration = $this->getConfiguration([], $container);
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerHooks($container, $config['hooks'], $config['supported_hookable_types']);
        $container->setParameter('twig_hooks.enable_autoprefixing', $config['enable_autoprefixing']);
    }

    /**
     * @param array<string, array<string, array{
     *      type: string,
     *      target: string,
     *      context: array<string, mixed>,
     *      configuration: array<string, mixed>,
     *      priority: int,
     *      enabled: bool,
     *  }>> $hooks
     * @param array<string, string> $supportedHookableTypes
     */
    private function registerHooks(ContainerBuilder $container, array $hooks, array $supportedHookableTypes): void
    {
        foreach ($hooks as $hookName => $hookables) {
            foreach ($hookables as $hookableName => $hookable) {
                if (!array_key_exists($hookable['type'], $supportedHookableTypes)) {
                    throw new \InvalidArgumentException(sprintf('Hookable type "%s" is not supported.', $hookable['type']));
                }

                $this->registerHookable(
                    $container,
                    $supportedHookableTypes[$hookable['type']],
                    $hookName,
                    $hookableName,
                    $hookable,
                );
            }
        }
    }

    /**
     * @param array{
     *     type: string,
     *     target: string,
     *     props?: array<string, mixed>,
     *     context: array<string, mixed>,
     *     configuration: array<string, mixed>,
     *     priority: int,
     *     enabled: bool,
     * } $hookable
     */
    private function registerHookable(
        ContainerBuilder $container,
        string $class,
        string $hookName,
        string $hookableName,
        array $hookable,
    ): void {
        match ($class) {
            HookableTemplate::class => $this->registerTemplateHookable($container, $hookName, $hookableName, $hookable),
            HookableComponent::class => $this->registerComponentHookable($container, $hookName, $hookableName, $hookable),
            default => throw new \InvalidArgumentException(sprintf('Unsupported hookable class "%s".', $class)),
        };
    }

    /**
     * @param array{
     *     type: string,
     *     target: string,
     *     context: array<string, mixed>,
     *     configuration: array<string, mixed>,
     *     priority: int,
     *     enabled: bool,
     * } $hookable
     */
    private function registerTemplateHookable(
        ContainerBuilder $container,
        string $hookName,
        string $hookableName,
        array $hookable,
    ): void {
        $container
            ->register(sprintf('twig_hooks.hook.%s.hookable.%s', $hookName, $hookableName), HookableTemplate::class)
            ->setArguments([
                $hookName,
                $hookableName,
                $hookable['target'],
                $hookable['context'],
                $hookable['configuration'],
                $hookable['priority'],
                $hookable['enabled'],
            ])
            ->addTag('twig_hooks.hookable', ['priority' => $hookable['priority']])
        ;
    }

    /**
     * @param array{
     *     type: string,
     *     target: string,
     *     props?: array<string, mixed>,
     *     context: array<string, mixed>,
     *     configuration: array<string, mixed>,
     *     priority: int,
     *     enabled: bool,
     * } $hookable
     */
    private function registerComponentHookable(
        ContainerBuilder $container,
        string $hookName,
        string $hookableName,
        array $hookable,
    ): void {
        $container
            ->register(sprintf('twig_hooks.hook.%s.hookable.%s', $hookName, $hookableName), HookableComponent::class)
            ->setArguments([
                $hookName,
                $hookableName,
                $hookable['target'],
                $hookable['props'] ?? [],
                $hookable['context'],
                $hookable['configuration'],
                $hookable['priority'],
                $hookable['enabled'],
            ])
            ->addTag('twig_hooks.hookable', ['priority' => $hookable['priority']])
        ;
    }
}
