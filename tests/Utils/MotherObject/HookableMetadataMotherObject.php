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

    public static function withContext(ParameterBagInterface $context): HookableMetadata
    {
        return new HookableMetadata(
            HookMetadataMotherObject::some(),
            $context,
            new ParameterBag([]),
            []
        );
    }

    public static function withConfiguration(ParameterBagInterface $configuration): HookableMetadata
    {
        return new HookableMetadata(
            HookMetadataMotherObject::some(),
            new ParameterBag([]),
            $configuration,
            []
        );
    }

    public static function withContextAndConfiguration(ParameterBagInterface $context, ParameterBagInterface $configuration): HookableMetadata
    {
        return new HookableMetadata(
            HookMetadataMotherObject::some(),
            $context,
            $configuration,
            []
        );
    }
}
