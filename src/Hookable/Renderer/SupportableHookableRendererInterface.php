<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer;

use Sylius\TwigHooks\Hookable\AbstractHookable;

interface SupportableHookableRendererInterface extends HookableRendererInterface
{
    public function supports(AbstractHookable $hookable): bool;
}
