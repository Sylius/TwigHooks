<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Provider;

use Sylius\TwigHooks\Hookable\AbstractHookable;

final class DefaultConfigurationProvider implements ConfigurationProviderInterface
{
    public function provide(AbstractHookable $hookable): array
    {
        return $hookable->getConfiguration();
    }
}
