<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Utils\MotherObject;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\BaseHookable;

final class BaseHookableMotherObject
{
    public static function some(): BaseHookable
    {
        return new BaseHookable('some_hook', 'some_name', AbstractHookable::TYPE_TEMPLATE, 'some_target');
    }

    public static function withType(string $type): BaseHookable
    {
        return new BaseHookable('some_hook', 'some_name', $type, 'some_target');
    }

    public static function withName(string $name): BaseHookable
    {
        return new BaseHookable('some_hook', $name, AbstractHookable::TYPE_TEMPLATE, 'some_target');
    }

    public static function withTypeAndTarget(string $type, string $target): BaseHookable
    {
        return new BaseHookable('some_hook', 'some_name', $type, $target);
    }

    public static function withTarget(string $target): BaseHookable
    {
        return new BaseHookable('some_hook', 'some_name', AbstractHookable::TYPE_TEMPLATE, $target);
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public static function withConfiguration(array $configuration): BaseHookable
    {
        return new BaseHookable('some_hook', 'some_name', AbstractHookable::TYPE_TEMPLATE, 'some_target', configuration: $configuration);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function withData(array $data): BaseHookable
    {
        return new BaseHookable('some_hook', 'some_name', AbstractHookable::TYPE_TEMPLATE, 'some_target', data: $data);
    }
}
