<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Renderer;

interface HookRendererInterface
{
    /**
     * @param array<string> $hookNames
     * @param array<string, mixed> $hookContext
     */
    public function render(array $hookNames, array $hookContext = []): string;
}
