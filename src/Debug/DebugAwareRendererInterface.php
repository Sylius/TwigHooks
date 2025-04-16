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

namespace Sylius\TwigHooks\Debug;

interface DebugAwareRendererInterface
{
    public const CONTEXT_DEBUG_PREFIX = '_debug_prefix';

    public const CONTEXT_DEBUG_SUFFIX = '_debug_suffix';

    public const DEFAULT_DEBUG_PREFIX = '<!--';

    public const DEFAULT_DEBUG_SUFFIX = '-->';
}
