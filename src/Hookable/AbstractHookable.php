<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable;

abstract class AbstractHookable
{
    public readonly string $id;

    public readonly string $hookName;

    public readonly string $name;

    public readonly array $context;

    public readonly array $configuration;

    private readonly ?int $priority;

    public const DEFAULT_PRIORITY = 0;

    /**
     * @param array<string, mixed> $context
     * @param array<string, mixed> $configuration
     */
    public function __construct (
        string $hookName,
        string $name,
        array $context = [],
        array $configuration = [],
        ?int $priority = null,
    ) {
        $this->id = sprintf('%s#%s', $hookName, $name);
        $this->hookName = $hookName;
        $this->name = $name;
        $this->context = $context;
        $this->configuration = $configuration;
        $this->priority = $priority;
    }

    public function priority(): int
    {
        return $this->priority ?? self::DEFAULT_PRIORITY;
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;
}
