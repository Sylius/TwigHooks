<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Twig\Runtime;

use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class HooksRuntime implements RuntimeExtensionInterface
{
    public const HOOKABLE_CONFIGURATION_PARAMETER = 'hookable_configuration';

    public const HOOKABLE_DATA_PARAMETER = 'hookable_data';

    public function __construct (
        private readonly HookRendererInterface $hookRenderer,
    ) {
    }

    public function createHookName(string $base, string ...$parts): string
    {
        if ([] === $parts) {
            return $this->formatBaseString($base);
        }

        return sprintf(
            '%s.%s',
            $this->formatBaseString($base),
            implode('.', $parts),
        );
    }

    private function formatBaseString(string $base): string
    {
        $parts = explode('/', $base);
        $resultParts = [];

        foreach ($parts as $part) {
            $resultPart = trim($part, '_');
            $resultPart = str_replace(['@', '.html.twig'], '', $resultPart);
            /** @var string $resultPart */
            $resultPart = preg_replace('/(?<!^)[A-Z]/', '_$0', $resultPart);
            $resultPart = strtolower($resultPart);
            $resultParts[] = $resultPart;
        }

        return implode('.', $resultParts);
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
