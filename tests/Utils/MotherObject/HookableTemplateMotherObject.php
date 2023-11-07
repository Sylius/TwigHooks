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
     * @param array<string, mixed> $data
     */
    public static function withData(array $data): HookableTemplate
    {
        return new HookableTemplate('some_hook', 'some_name', 'some_target', data: $data);
    }
}
