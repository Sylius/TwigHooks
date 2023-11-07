<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Renderer;

use Sylius\TwigHooks\Hookable\Renderer\HookableRendererInterface;
use Sylius\TwigHooks\Registry\HookablesRegistry;

final class HookRenderer implements HookRendererInterface
{
    public function __construct(
        private HookablesRegistry $hookablesRegistry,
        private HookableRendererInterface $compositeHookableRenderer,
    ) {
    }

    public function render(array|string $hooksNames, array $data = []): string
    {
        $hookables = $this->hookablesRegistry->getEnabledFor($hooksNames);
        $renderedHookables = [];

        foreach ($hookables as $hookable) {
            $renderedHookables[] = $this->compositeHookableRenderer->render($hookable, $data);
        }

        return implode(PHP_EOL, $renderedHookables);
    }
}
