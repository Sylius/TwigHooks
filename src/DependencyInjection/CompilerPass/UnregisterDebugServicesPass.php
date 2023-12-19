<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class UnregisterDebugServicesPass implements CompilerPassInterface
{
    public const DEBUG_TAG = 'sylius_twig_hooks.debug';

    public function process(ContainerBuilder $container): void
    {
        $debug = !$container->hasParameter('kernel.debug') || $container->getParameter('kernel.debug');

        if (true === $debug) {
            return;
        }

        $debugServices = $container->findTaggedServiceIds(self::DEBUG_TAG);

        foreach ($debugServices as $id => $tags) {
            $container->removeDefinition($id);
        }
    }
}
