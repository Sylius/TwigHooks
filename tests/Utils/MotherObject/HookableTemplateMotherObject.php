<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Utils\MotherObject;

use Sylius\TwigHooks\Hookable\HookableTemplate;

final class HookableTemplateMotherObject
{
    public static function some(): HookableTemplate
    {
        return new HookableTemplate('some_hook', 'some_name', 'some_target');
    }

    public static function with(array $parameters): HookableTemplate
    {
        if (!array_key_exists('hookName', $parameters)) {
            $parameters['hookName'] = 'some_hook';
        }

        if (!array_key_exists('name', $parameters)) {
            $parameters['name'] = 'some_name';
        }

        if (!array_key_exists('template', $parameters)) {
            $parameters['template'] = 'some_target';
        }

        return new HookableTemplate(...$parameters);
    }

    public static function withName(string $name): HookableTemplate
    {
        return new HookableTemplate('some_hook', $name, 'some_target');
    }

    public static function withTarget(string $target): HookableTemplate
    {
        return new HookableTemplate('some_hook', 'some_name', $target);
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public static function withConfiguration(array $configuration): HookableTemplate
    {
        return new HookableTemplate('some_hook', 'some_name', 'some_target', configuration: $configuration);
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function withContext(array $context): HookableTemplate
    {
        return new HookableTemplate('some_hook', 'some_name', 'some_target', context: $context);
    }

    public static function withPriority(?int $priority): HookableTemplate
    {
        return new HookableTemplate('some_hook', 'some_name', 'some_target', priority: $priority);
    }
}
