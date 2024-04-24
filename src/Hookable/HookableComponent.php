<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable;

class HookableComponent extends AbstractHookable
{
    /**
     * @param array<string, mixed> $props
     * @param array<string, mixed> $context
     * @param array<string, mixed> $configuration
     */
    public function __construct (
        string $hookName,
        string $name,
        public readonly string $component,
        public readonly array $props = [],
        array $context = [],
        array $configuration = [],
        ?int $priority = null,
    ) {
        parent::__construct($hookName, $name, $context, $configuration, $priority);
    }

    public function toArray(): array
    {
        return [
            'hookName' => $this->hookName,
            'name' => $this->name,
            'component' => $this->component,
            'props' => $this->props,
            'context' => $this->context,
            'configuration' => $this->configuration,
            'priority' => $this->priority(),
        ];
    }
}
