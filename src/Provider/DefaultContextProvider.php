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

namespace Sylius\TwigHooks\Provider;

use Sylius\TwigHooks\Hookable\AbstractHookable;

final class DefaultContextProvider implements ContextProviderInterface
{
    public function provide(AbstractHookable $hookable, array $hookContext): array
    {
        return array_merge($hookContext, $hookable->context);
    }
}
