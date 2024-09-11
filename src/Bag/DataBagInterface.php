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

namespace Sylius\TwigHooks\Bag;

/**
 * @extends \ArrayAccess<string, mixed>
 */
interface DataBagInterface extends \ArrayAccess
{
    public function has(string $name): bool;

    /**
     * @return array<string, mixed>
     */
    public function all(): array;
}
