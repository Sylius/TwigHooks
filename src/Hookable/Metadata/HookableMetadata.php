<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Metadata;

use Sylius\TwigHooks\Hook\Metadata\HookMetadata;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class HookableMetadata
{
    /**
     * @param array<string> $prefixes
     */
    public function __construct(
        public readonly HookMetadata $renderedBy,
        public readonly ParameterBagInterface $context,
        public readonly ParameterBagInterface $configuration,
        public readonly array $prefixes = [],
    ) {
        foreach ($prefixes as $prefix) {
            if (!is_string($prefix)) {
                throw new \InvalidArgumentException('Parent name must be a string.');
            }
        }
    }

    public function hasPrefixes(): bool
    {
        return count($this->prefixes) > 0;
    }
}
