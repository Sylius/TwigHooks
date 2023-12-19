<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Renderer\Debug;

use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;
use Sylius\TwigHooks\Profiler\Profile;
use Symfony\Component\Stopwatch\Stopwatch;

final class HookProfilerRenderer implements HookRendererInterface
{
    public function __construct (
        private HookRendererInterface $innerRenderer,
        private ?Profile $profile,
        private ?Stopwatch $stopwatch,
    ) {
    }

    public function render(array $hooksNames, array $data = []): string
    {
        $this->profile?->registerHookStart($hooksNames);
        $this->stopwatch?->start(md5(serialize($hooksNames)));

        $rendered = $this->innerRenderer->render($hooksNames, $data);

        $this->profile?->registerHookEnd(
            $this->stopwatch?->stop(md5(serialize($hooksNames)))->getDuration(),
        );

        return $rendered;
    }
}
