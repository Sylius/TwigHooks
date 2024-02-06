<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable;

abstract class AbstractHookable
{
    public const DEFAULT_PRIORITY = 0;

    public const TYPE_COMPONENT = 'component';

    public const TYPE_TEMPLATE = 'template';

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $configuration
     */
    public function __construct (
        protected string $hookName,
        protected string $name,
        protected string $type,
        protected string $target,
        protected array $data = [],
        protected array $configuration = [],
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

    public function getType(): string
    {
        return $this->type;
    }

    public function isType(string $type): bool
    {
        return $this->type === $type;
    }

    public function isComponentType(): bool
    {
        return $this->isType(self::TYPE_COMPONENT);
    }

    public function isTemplateType(): bool
    {
        return $this->isType(self::TYPE_TEMPLATE);
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

    public function overwriteWith(self $hookable): self
    {
        if ($hookable->getName() !== $this->getName()) {
            throw new \InvalidArgumentException('Hookable cannot be overwritten with different name.');
        }

        return new static(
            $hookable->getHookName(),
            $hookable->getName(),
            $hookable->getType(),
            $hookable->getTarget(),
            array_merge($this->getData(), $hookable->data),
            array_merge($this->getConfiguration(), $hookable->configuration),
            $hookable->priority ?? $this->getPriority(),
            $hookable->enabled ?? $this->isEnabled(),
        );
    }
}
