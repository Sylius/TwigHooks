<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Metadata;

use Sylius\TwigHooks\Bag\DataBagInterface;

class HookMetadata
{
    public function __construct(
        public readonly string $name,
        public readonly DataBagInterface $context,
    ) {
    }
}
