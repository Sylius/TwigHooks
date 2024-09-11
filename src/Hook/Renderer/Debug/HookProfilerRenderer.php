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

use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;
use Sylius\TwigHooks\Profiler\Profile;
use Symfony\Component\Stopwatch\Stopwatch;

final class HookProfilerRenderer implements HookRendererInterface
{
    public function __construct(
        private HookRendererInterface $innerRenderer,
        private ?Profile $profile,
        private ?Stopwatch $stopwatch,
    ) {
    }

    public function render(array $hookNames, array $hookContext = []): string
    {
        $this->profile?->registerHookStart($hookNames);
        $this->stopwatch?->start(md5(serialize($hookNames)));

        $rendered = $this->innerRenderer->render($hookNames, $hookContext);

        $this->profile?->registerHookEnd(
            $this->stopwatch?->stop(md5(serialize($hookNames)))->getDuration(),
        );

        return $rendered;
    }
}
