<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable;

abstract class AbstractHookable
{
    public const DEFAULT_PRIORITY = 0;

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $configuration
     */
    public function __construct (
        protected string $hookName,
        protected string $name,
        protected string $target,
        protected ?array $data = null,
        protected ?array $configuration = null,
        protected ?int $priority = null,
        protected ?bool $enabled = null,
    ) {
    }

    public function getHookName(): string
    {
        return $this->hookName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return sprintf('%s#%s', $this->hookName, $this->name);
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    /** @return array<string, mixed> */
    public function getData(): array
    {
        return $this->data ?? [];
    }

    /** @return array<string, mixed> */
    public function getConfiguration(): array
    {
        return $this->configuration ?? [];
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

    abstract public function getTypeName(): string;
}
