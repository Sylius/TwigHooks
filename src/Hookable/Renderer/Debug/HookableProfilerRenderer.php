<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer\Debug;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Renderer\HookableRendererInterface;
use Sylius\TwigHooks\Profiler\Profile;
use Symfony\Component\Stopwatch\Stopwatch;

final class HookableProfilerRenderer implements HookableRendererInterface
{
    public function __construct (
        private readonly HookableRendererInterface $innerRenderer,
        private readonly ?Profile $profile,
        private readonly ?Stopwatch $stopwatch,
    ) {
    }

    public function render(AbstractHookable $hookable, array $hookData = []): string
    {
        $this->profile?->registerHookableRenderStart($hookable);
        $this->stopwatch?->start($hookable->getId());

        $rendered = $this->innerRenderer->render($hookable, $hookData);

        $this->profile?->registerHookableRenderEnd(
            $this->stopwatch?->stop($hookable->getId())->getDuration(),
        );

        return $rendered;
    }
}
