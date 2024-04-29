<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Utils\MotherObject;

use Sylius\TwigHooks\Bag\DataBag;
use Sylius\TwigHooks\Bag\DataBagInterface;
use Sylius\TwigHooks\Bag\ScalarDataBag;
use Sylius\TwigHooks\Bag\ScalarDataBagInterface;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;

final class HookableMetadataMotherObject
{
    public static function some(): HookableMetadata
    {
        return new HookableMetadata(
            HookMetadataMotherObject::some(),
            new DataBag([]),
            new ScalarDataBag([]),
            []
        );
    }

    public static function withContext(DataBagInterface|array $context): HookableMetadata
    {
        return new HookableMetadata(
            HookMetadataMotherObject::some(),
            is_array($context) ? new DataBag($context) : $context,
            new ScalarDataBag([]),
            []
        );
    }

    public static function withConfiguration(ScalarDataBagInterface|array $configuration): HookableMetadata
    {
        return new HookableMetadata(
            HookMetadataMotherObject::some(),
            new DataBag([]),
            is_array($configuration) ? new ScalarDataBag($configuration) : $configuration,
            []
        );
    }

    public static function withContextAndConfiguration(
        DataBagInterface|array $context,
        ScalarDataBagInterface|array $configuration,
    ): HookableMetadata {
        return new HookableMetadata(
            HookMetadataMotherObject::some(),
            is_array($context) ? new DataBag($context) : $context,
            is_array($configuration) ? new ScalarDataBag($configuration) : $configuration,
            []
        );
    }
}
