<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Twig\Runtime;

use Sylius\TwigHooks\Hook\NameGenerator\NameGeneratorInterface;
use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class HooksRuntime implements RuntimeExtensionInterface
{
    public const HOOKABLE_CONFIGURATION_PARAMETER = 'hookable_configuration';

    public const HOOKABLE_DATA_PARAMETER = 'hookable_data';

    public function __construct (
        private readonly HookRendererInterface $hookRenderer,
        private readonly NameGeneratorInterface $nameGenerator,
    ) {
    }

    public function createHookName(string $base, string ...$parts): string
    {
        return $this->nameGenerator->generate($base, ...$parts);
    }

    /**
     * @param array<string> $hooksNames
     * @param array<string, mixed> $data
     */
    public function renderHook(string|array $hooksNames, array $data = []): string
    {
        if (is_string($hooksNames)) {
            $hooksNames = [$hooksNames];
        }

        return $this->hookRenderer->render($hooksNames, $data);
    }
}
