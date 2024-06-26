<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable;

class HookableTemplate extends AbstractHookable
{
    public function __construct(
        string $hookName,
        string $name,
        public readonly string $template,
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
            'template' => $this->template,
            'context' => $this->context,
            'configuration' => $this->configuration,
            'priority' => $this->priority(),
        ];
    }
}
