<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable;

class HookableTemplate extends AbstractHookable
{
    public const TYPE_NAME = 'template';

    public function getType(): string
    {
        return self::TYPE_NAME;
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
            array_merge($this->context, $hookable->context),
            array_merge($this->configuration, $hookable->configuration),
            $hookable->getPriority(),
            $hookable->isEnabled(),
        );
    }

    public function toArray(): array
    {
        return [
            'hookName' => $this->hookName,
            'name' => $this->name,
            'target' => $this->target,
            'context' => $this->context,
            'configuration' => $this->configuration,
            'priority' => $this->getPriority(),
            'enabled' => $this->isEnabled(),
        ];
    }
}
