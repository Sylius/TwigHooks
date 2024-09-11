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

namespace Sylius\TwigHooks\Profiler;

use Sylius\TwigHooks\Hookable\AbstractHookable;

/** @internal */
class HookableProfile
{
    private int|float|null $duration = null;

    /**
     * @param array<HookProfile> $children
     */
    public function __construct(
        private HookProfile $parent,
        private string $name,
        private AbstractHookable $hookable,
        private array $children,
    ) {
    }

    public function getParent(): HookProfile
    {
        return $this->parent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHookable(): AbstractHookable
    {
        return $this->hookable;
    }

    public function addChild(HookProfile $child): void
    {
        $this->children[] = $child;
    }

    /**
     * @return array<HookProfile>
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function setDuration(int|float $duration): void
    {
        $this->duration = $duration;
    }

    public function getDuration(): int|float|null
    {
        return $this->duration;
    }
}
