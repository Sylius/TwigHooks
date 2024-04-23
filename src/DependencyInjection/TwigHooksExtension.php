<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\DependencyInjection;

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
    }

    /**
     * @param array<string, array<string, array{
     *      type: string,
     *      target: string,
     *      data: array<string, mixed>,
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
     *     data: array<string, mixed>,
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
        $container
            ->register(sprintf('twig_hooks.hook.%s.hookable.%s', $hookName, $hookableName), $class)
            ->setArguments([
                $hookName,
                $hookableName,
                $hookable['type'],
                $hookable['target'],
                $hookable['data'],
                $hookable['configuration'],
                $hookable['priority'],
                $hookable['enabled'],
            ])
            ->addTag('twig_hooks.hookable', ['priority' => $hookable['priority']])
        ;
    }
}
