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

namespace Sylius\TwigHooks\Hookable\Renderer\Debug;

use Sylius\TwigHooks\Debug\DebugAwareRendererInterface;
use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Hookable\HookableTemplate;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Hookable\Renderer\HookableRendererInterface;

final class HookableDebugCommentRenderer implements HookableRendererInterface, DebugAwareRendererInterface
{
    public function __construct(private readonly HookableRendererInterface $innerRenderer)
    {
    }

    public function render(AbstractHookable $hookable, HookableMetadata $metadata): string
    {
        $renderedParts = [];
        $renderedParts[] = $this->getDebugComment(
            $hookable,
            '%s BEGIN HOOKABLE | hook: "%s", name: "%s", %s: "%s", priority: %d %s',
        );
        $renderedParts[] = trim($this->innerRenderer->render($hookable, $metadata));
        $renderedParts[] = $this->getDebugComment(
            $hookable,
            '%s  END HOOKABLE  | hook: "%s", name: "%s", %s: "%s", priority: %d %s',
        );

        return implode(\PHP_EOL, $renderedParts);
    }

    private function getDebugComment(AbstractHookable $hookable, string $format): string
    {
        [$targetName, $targetValue] = match (get_class($hookable)) {
            HookableTemplate::class => ['template', $hookable->template],
            HookableComponent::class => ['component', $hookable->component],
            default => throw new \InvalidArgumentException('Unsupported hookable type.'),
        };

        $commentPrefix = $hookable->context[self::CONTEXT_DEBUG_PREFIX] ?? self::DEFAULT_DEBUG_PREFIX;
        $commentSuffix = $hookable->context[self::CONTEXT_DEBUG_SUFFIX] ?? self::DEFAULT_DEBUG_SUFFIX;

        return sprintf(
            $format,
            $commentPrefix,
            $hookable->hookName,
            $hookable->name,
            $targetName,
            $targetValue,
            $hookable->priority(),
            $commentSuffix,
        );
    }
}
