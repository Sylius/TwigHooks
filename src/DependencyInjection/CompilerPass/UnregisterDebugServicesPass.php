<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class UnregisterDebugServicesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $debug = $container->getParameter('kernel.debug');
        if (true === $debug) {
            return;
        }

        $container->removeDefinition('twig_hooks.renderer.hook.debug');
        $container->removeDefinition('twig_hooks.renderer.hookable.debug');
    }
}
