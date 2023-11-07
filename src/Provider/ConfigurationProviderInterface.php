<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Provider;

use Sylius\TwigHooks\Hookable\AbstractHookable;

interface ConfigurationProviderInterface
{
    /**
     * @return array<string, mixed>
     */
    public function provide(AbstractHookable $hookable): array;
}
