<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Merger;

use Sylius\TwigHooks\Hookable\AbstractHookable;

interface HookableMergerInterface
{
    public function merge(AbstractHookable ...$hookables): AbstractHookable;
}
