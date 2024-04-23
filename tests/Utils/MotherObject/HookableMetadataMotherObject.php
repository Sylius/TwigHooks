<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Utils\MotherObject;

use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class HookableMetadataMotherObject
{
    public static function some(): HookableMetadata
    {
        return new HookableMetadata(
            HookMetadataMotherObject::some(),
            new ParameterBag([]),
            new ParameterBag([]),
            []
        );
    }

    public static function withContext(ParameterBagInterface|array $context): HookableMetadata
    {
        return new HookableMetadata(
            HookMetadataMotherObject::some(),
            is_array($context) ? new ParameterBag($context) : $context,
            new ParameterBag([]),
            []
        );
    }

    public static function withConfiguration(ParameterBagInterface|array $configuration): HookableMetadata
    {
        return new HookableMetadata(
            HookMetadataMotherObject::some(),
            new ParameterBag([]),
            is_array($configuration) ? new ParameterBag($configuration) : $configuration,
            []
        );
    }

    public static function withContextAndConfiguration(
        ParameterBagInterface|array $context,
        ParameterBagInterface|array $configuration,
    ): HookableMetadata {
        return new HookableMetadata(
            HookMetadataMotherObject::some(),
            is_array($context) ? new ParameterBag($context) : $context,
            is_array($configuration) ? new ParameterBag($configuration) : $configuration,
            []
        );
    }
}
