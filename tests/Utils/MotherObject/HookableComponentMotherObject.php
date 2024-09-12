<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Utils\MotherObject;

use Sylius\TwigHooks\Hookable\HookableComponent;

final class HookableComponentMotherObject
{
    public static function some(): HookableComponent
    {
        return new HookableComponent('some_hook', 'some_name', 'some_target');
    }

    public static function with(array $parameters): HookableComponent
    {
        if (!array_key_exists('hookName', $parameters)) {
            $parameters['hookName'] = 'some_hook';
        }

        if (!array_key_exists('name', $parameters)) {
            $parameters['name'] = 'some_name';
        }

        if (!array_key_exists('component', $parameters)) {
            $parameters['component'] = 'some_target';
        }

        return new HookableComponent(...$parameters);
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
}
