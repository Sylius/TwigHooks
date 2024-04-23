<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;

interface HookableRendererInterface
{
    public function render(AbstractHookable $hookable, HookableMetadata $metadata): string;
}
