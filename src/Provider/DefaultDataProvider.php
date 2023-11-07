<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Provider;

use Sylius\TwigHooks\Hookable\AbstractHookable;

final class DefaultDataProvider implements DataProviderInterface
{
    public function provide(AbstractHookable $hookable, array $hookData): array
    {
        return array_replace($hookable->getData(), $hookData);
    }
}
