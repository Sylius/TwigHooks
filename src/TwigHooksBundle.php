<?php

declare(strict_types=1);

namespace Sylius\TwigHooks;

use Sylius\TwigHooks\DependencyInjection\CompilerPass\UnregisterDebugServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class TwigHooksBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new UnregisterDebugServicesPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
