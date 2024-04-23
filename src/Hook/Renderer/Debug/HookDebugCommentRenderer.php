<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Renderer\Debug;

use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;

final class HookDebugCommentRenderer implements HookRendererInterface
{
    public function __construct(private readonly HookRendererInterface $innerRenderer)
    {
    }

    public function render(array $hookNames, array $hookContext = []): string
    {
        $renderedParts = [];
        $renderedParts[] = $this->getOpeningDebugComment($hookNames);
        $renderedParts[] = trim($this->innerRenderer->render($hookNames, $hookContext));
        $renderedParts[] = $this->getClosingDebugComment($hookNames);

        return implode(PHP_EOL, $renderedParts);
    }

    /**
     * @param string|array<string> $hooksNames
     */
    private function getOpeningDebugComment(string|array $hooksNames): string
    {
        return sprintf(
            '<!-- BEGIN HOOK | name: "%s" -->',
            is_string($hooksNames) ? $hooksNames : implode(', ', $hooksNames),
        );
    }

    /**
     * @param string|array<string> $hooksNames
     */
    private function getClosingDebugComment(string|array $hooksNames): string
    {
        return sprintf(
            '<!--  END HOOK  | name: "%s" -->',
            is_string($hooksNames) ? $hooksNames : implode(', ', $hooksNames),
        );
    }
}
