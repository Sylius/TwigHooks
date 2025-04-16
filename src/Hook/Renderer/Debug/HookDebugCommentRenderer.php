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

namespace Sylius\TwigHooks\Hook\Renderer\Debug;

use Sylius\TwigHooks\Debug\DebugAwareRendererInterface;
use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;

final class HookDebugCommentRenderer implements HookRendererInterface, DebugAwareRendererInterface
{
    public function __construct(private readonly HookRendererInterface $innerRenderer)
    {
    }

    public function render(array $hookNames, array $hookContext = []): string
    {
        $renderedParts = [];
        $renderedParts[] = $this->getDebugComment(
            $hookNames,
            $hookContext,
            '%s BEGIN HOOK | name: "%s" %s',
        );
        $renderedParts[] = trim($this->innerRenderer->render($hookNames, $hookContext));
        $renderedParts[] = $this->getDebugComment(
            $hookNames,
            $hookContext,
            '%s  END HOOK  | name: "%s" %s',
        );

        return implode(\PHP_EOL, $renderedParts);
    }

    /**
     * @param string|array<string> $hooksNames
     * @param array<string, mixed> $hookContext
     */
    private function getDebugComment(string|array $hooksNames, array $hookContext, string $format): string
    {
        $commentPrefix = $hookContext[self::CONTEXT_DEBUG_PREFIX] ?? self::DEFAULT_DEBUG_PREFIX;
        $commentSuffix = $hookContext[self::CONTEXT_DEBUG_SUFFIX] ?? self::DEFAULT_DEBUG_SUFFIX;

        return sprintf(
            $format,
            $commentPrefix,
            is_string($hooksNames) ? $hooksNames : implode(', ', $hooksNames),
            $commentSuffix,
        );
    }
}
