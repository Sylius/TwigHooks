<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Provider;

use Sylius\TwigHooks\Hookable\AbstractHookable;

interface ContextProviderInterface
{
    /**
     * @param array<string, mixed> $hookContext
     * @return array<string, mixed>
     */
    public function provide(AbstractHookable $hookable, array $hookContext): array;
}
