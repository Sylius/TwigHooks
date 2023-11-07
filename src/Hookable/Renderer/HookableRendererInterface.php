<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer;

use Sylius\TwigHooks\Hookable\AbstractHookable;

interface HookableRendererInterface
{
    /**
     * @param array<string, mixed> $hookData
     */
    public function render(AbstractHookable $hookable, array $hookData = []): string;
}
