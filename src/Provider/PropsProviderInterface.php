<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Provider;

use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Provider\Exception\InvalidExpressionException;

interface PropsProviderInterface
{
    /**
     * @throws InvalidExpressionException
     * @return array<string, mixed>
     */
    public function provide(HookableComponent $hookable, HookableMetadata $metadata): array;
}
