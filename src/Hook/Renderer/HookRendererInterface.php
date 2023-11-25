<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Renderer;

interface HookRendererInterface
{
    /**
     * @param array<string> $hooksNames
     * @param array<string, mixed> $data
     */
    public function render(array $hooksNames, array $data = []): string;
}
