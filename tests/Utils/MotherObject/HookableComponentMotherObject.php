<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Utils\MotherObject;

use Sylius\TwigHooks\Hookable\HookableComponent;

final class HookableComponentMotherObject
{
    public static function some(): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', 'some_target');
    }

    public static function withName(string $name): HookableComponent
    {
        return new HookableComponent('some_hook', $name, 'some_target');
    }

    public static function withTarget(string $target): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', $target);
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public static function withConfiguration(array $configuration): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', 'some_target', configuration: $configuration);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function withData(array $data): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', 'some_target', data: $data);
    }
}
