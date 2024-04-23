<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Metadata;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class HookMetadata
{
    public function __construct(
        public readonly string $name,
        public readonly ParameterBagInterface $context,
    ) {
    }
}
