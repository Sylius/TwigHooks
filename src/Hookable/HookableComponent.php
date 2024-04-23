<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable;

class HookableComponent extends AbstractHookable
{
    public const TYPE_NAME = 'component';

    /**
     * @param array<string, mixed> $props
     * @param array<string, mixed> $context
     * @param array<string, mixed> $configuration
     */
    public function __construct (
        public readonly string $hookName,
        public readonly string $name,
        public readonly string $target,
        public readonly array $props = [],
        public readonly array $context = [],
        public readonly array $configuration = [],
        protected readonly ?int $priority = null,
        protected readonly ?bool $enabled = null,
    ) {
    }

    public function getType(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @return array<string, mixed>
     */
    public function getProps(): array
    {
        return $this->props;
    }

    public function overwriteWith(AbstractHookable $hookable): AbstractHookable
    {
        if ($hookable->name !== $this->name) {
            throw new \InvalidArgumentException(sprintf(
                'Cannot overwrite hookable with different name. Expected "%s", got "%s".',
                $this->name,
                $hookable->name,
            ));
        }

        return new self(
            $hookable->hookName,
            $this->name,
            $hookable->target,
            $hookable instanceof HookableComponent ? array_merge($this->props, $hookable->props) : [],
            array_merge($this->context, $hookable->context),
            array_merge($this->configuration, $hookable->configuration),
            $hookable->getPriority(),
            $hookable->isEnabled(),
        );
    }
}
