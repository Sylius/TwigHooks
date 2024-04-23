<?php

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
