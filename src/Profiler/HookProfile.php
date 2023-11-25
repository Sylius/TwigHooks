<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Profiler;

/** @internal */
class HookProfile
{
    private int|float|null $duration = null;

    /**
     * @param array<string> $hooksNames
     * @param array<HookableProfile> $hookablesProfiles
     */
    public function __construct (
        private array $hooksNames,
        private array $hookablesProfiles,
        private ?HookProfile $parent = null,
    ) {
    }

    public function getParent(): ?HookProfile
    {
        return $this->parent;
    }

    public function getName(): string
    {
        return implode(', ', $this->hooksNames);
    }

    public function addHookableProfile(HookableProfile $hookableProfile): void
    {
        $this->hookablesProfiles[] = $hookableProfile;
    }

    /**
     * @return array<HookableProfile>
     */
    public function getHookablesProfiles(): array
    {
        return $this->hookablesProfiles;
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
