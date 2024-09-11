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
class Profile
{
    /** @var array<HookProfile> */
    private array $rootProfiles = [];

    private ?HookProfile $previousHookProfile = null;

    private ?HookProfile $currentHookProfile = null;

    private ?HookableProfile $currentHookableProfile = null;

    private int $numberOfHooks = 0;

    private int $numberOfHookables = 0;

    /**
     * @param array<string> $hooksNames
     */
    public function registerHookStart(array $hooksNames): void
    {
        $this->previousHookProfile = $this->currentHookProfile;
        $hookProfile = new HookProfile($hooksNames, [], $this->previousHookProfile);

        $this->currentHookProfile = $hookProfile;
        $this->currentHookableProfile?->addChild($hookProfile);
    }

    public function registerHookEnd(int|float|null $duration = null): void
    {
        if (null !== $this->currentHookProfile && null === $this->currentHookProfile->getParent()) {
            $this->rootProfiles[] = $this->currentHookProfile;
        }

        if (null !== $duration) {
            $this->currentHookProfile?->setDuration($duration);
        }

        $this->previousHookProfile = $this->previousHookProfile?->getParent();
        $this->currentHookProfile = $this->currentHookProfile?->getParent();
        ++$this->numberOfHooks;
    }

    public function registerHookableRenderStart(AbstractHookable $hookable): void
    {
        if (null === $this->currentHookProfile) {
            throw new \RuntimeException('Cannot register hookable render without hook profile');
        }

        $hookableProfile = new HookableProfile($this->currentHookProfile, $hookable->name, $hookable, []);

        $this->currentHookableProfile = $hookableProfile;
        $this->currentHookProfile->addHookableProfile($this->currentHookableProfile);
        ++$this->numberOfHookables;
    }

    public function registerHookableRenderEnd(int|float|null $duration): void
    {
        if (null !== $duration) {
            $this->currentHookableProfile?->setDuration($duration);
        }

        $this->currentHookableProfile = null;
    }

    /**
     * @return array<HookProfile>
     */
    public function getRootProfiles(): array
    {
        return $this->rootProfiles;
    }

    public function getNumberOfHooks(): int
    {
        return $this->numberOfHooks;
    }

    public function getNumberOfHookables(): int
    {
        return $this->numberOfHookables;
    }

    public function getTotalDuration(): int|float
    {
        $totalDuration = 0;

        foreach ($this->rootProfiles as $rootProfile) {
            $totalDuration += $rootProfile->getDuration();
        }

        return $totalDuration;
    }

    public function reset(): void
    {
        $this->rootProfiles = [];
        $this->previousHookProfile = null;
        $this->currentHookProfile = null;
        $this->currentHookableProfile = null;
        $this->numberOfHooks = 0;
        $this->numberOfHookables = 0;
    }
}
