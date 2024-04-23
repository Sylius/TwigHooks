<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Twig;

use Sylius\TwigHooks\Twig\Runtime\HooksRuntime;
use Sylius\TwigHooks\Twig\TokenParser\HookTokenParser;
use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;
use Twig\TwigFunction;

final class HooksExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('hook_name', [HooksRuntime::class, 'createHookName']),
            new TwigFunction('create_hook_name', [HooksRuntime::class, 'createHookName']),
            new TwigFunction('get_hookable_metadata', [HooksRuntime::class, 'getHookableMetadata'], ['needs_context' => true]),
            new TwigFunction('get_hookable_context', [HooksRuntime::class, 'getHookableContext'], ['needs_context' => true]),
            new TwigFunction('get_hookable_configuration', [HooksRuntime::class, 'getHookableConfiguration'], ['needs_context' => true]),
        ];
    }

    /**
     * @return array<TokenParserInterface>
     */
    public function getTokenParsers(): array
    {
        return [
            new HookTokenParser(),
        ];
    }
}
