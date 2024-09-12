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

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Hookable\Renderer\HookableRendererInterface;
use Sylius\TwigHooks\Profiler\Profile;
use Symfony\Component\Stopwatch\Stopwatch;

final class HookableProfilerRenderer implements HookableRendererInterface
{
    public function __construct(
        private readonly HookableRendererInterface $innerRenderer,
        private readonly ?Profile $profile,
        private readonly ?Stopwatch $stopwatch,
    ) {
    }

    public function render(AbstractHookable $hookable, HookableMetadata $metadata): string
    {
        $this->profile?->registerHookableRenderStart($hookable);
        $this->stopwatch?->start($hookable->id);

        $rendered = $this->innerRenderer->render($hookable, $metadata);

        $this->profile?->registerHookableRenderEnd(
            $this->stopwatch?->stop($hookable->id)->getDuration(),
        );

        return $rendered;
    }
}
