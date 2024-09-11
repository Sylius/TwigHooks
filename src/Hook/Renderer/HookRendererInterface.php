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

namespace Sylius\TwigHooks\Hook\Renderer;

interface HookRendererInterface
{
    /**
     * @param array<string> $hookNames
     * @param array<string, mixed> $hookContext
     */
    public function render(array $hookNames, array $hookContext = []): string;
}
