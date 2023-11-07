<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Renderer;

interface HookRendererInterface
{
    public function render(string|array $hooksNames, array $data = []): string;
}
