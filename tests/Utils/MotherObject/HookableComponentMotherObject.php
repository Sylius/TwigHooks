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

    public static function withHookName(string $hookName): HookableComponent
    {
        return new HookableComponent($hookName, 'some_name', 'some_target');
    }

    public static function withName(string $name): HookableComponent
    {
        return new HookableComponent('some_hook', $name, 'some_target');
    }

    public static function withTarget(string $target): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', $target);
    }

    public static function withTargetAndProps(string $target, array $props): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', $target, props: $props);
    }

    public static function withProps(array $props): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', 'some_target', props: $props);
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public static function withConfiguration(array $configuration): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', 'some_target', configuration: $configuration);
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function withContext(array $context): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', 'some_target', context: $context);
    }

    public static function withPriority(?int $priority): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', 'some_target', priority: $priority);
    }

    public static function enabled(): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', 'some_target', enabled: true);
    }

    public static function disabled(): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', 'some_target', enabled: false);
    }
}
