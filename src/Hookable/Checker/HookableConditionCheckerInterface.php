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

namespace Sylius\TwigHooks\Hookable\Checker;

use Sylius\TwigHooks\Hookable\AbstractHookable;

interface HookableConditionCheckerInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function isEnabled(AbstractHookable $hookable, array $context): bool;
}
