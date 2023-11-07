<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Twig;

use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;
use Sylius\TwigHooks\Twig\Exception\NoHookNameProvidedException;
use Sylius\TwigHooks\Twig\TokenParser\HookTokenParser;
use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;
use Twig\TwigFunction;

final class HooksExtension extends AbstractExtension
{
    public function __construct (
        private HookRendererInterface $hookRenderer,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_hook_data', [$this, 'getHookData'], ['needs_context' => true]),
            new TwigFunction('get_hook_configuration', [$this, 'getHookData'], ['needs_context' => true]),
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

    /**
     * @param array{hook_data?: array<string, string>} $context
     * @return array<string, string>
     */
    public function getHookData(array $context): array
    {
        return $context['hook_data'] ?? [];
    }

    /**
     * @param array{hook_configuration?: array<string, string>} $context
     * @return array<string, string>
     */
    public function getHookConfiguration(array $context): array
    {
        return $context['hook_configuration'] ?? [];
    }

    /**
     * @param string|array<string> $hookNames
     * @param array<string, mixed> $data
     */
    public function render(string|array $hookNames, array $data = []): string
    {
        if (is_string($hookNames)) {
            $hookNames = [$hookNames];
        }

        $hookNames = array_filter(
            $hookNames,
            fn (?string $hookName) => $hookName !== null && $hookName !== '',
        );

        if ([] === $hookNames) {
            throw new NoHookNameProvidedException('No hook name provided');
        }

        return $this->hookRenderer->render($hookNames, $data) . PHP_EOL;
    }
}
