<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable;

abstract class AbstractHookable
{
    public const DEFAULT_PRIORITY = 0;

    /**
     * @param array<string, mixed> $context
     * @param array<string, mixed> $configuration
     */
    public function __construct (
        public readonly string $hookName,
        public readonly string $name,
        public readonly string $target,
        public readonly array $context = [],
        public readonly array $configuration = [],
        protected readonly ?int $priority = null,
        protected readonly ?bool $enabled = null,
    ) {
    }

    public function getId(): string
    {
        return sprintf('%s#%s', $this->hookName, $this->name);
    }

    public function getPriority(): int
    {
        return $this->priority ?? self::DEFAULT_PRIORITY;
    }

    public function isEnabled(): bool
    {
        return $this->enabled ?? true;
    }

    abstract public function overwriteWith(self $hookable): self;

    abstract public function getType(): string;

    /**
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;
}
