<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable;

class HookableTemplate extends AbstractHookable
{
    public function overwriteWith(AbstractHookable $hookable): HookableTemplate
    {
        if (!is_a($hookable, self::class) || $hookable->getName() !== $this->getName()) {
            throw new \InvalidArgumentException(sprintf('Hookable "%s" cannot be overwritten with "%s".', self::class, get_class($hookable)));
        }

        return new self(
            $hookable->getHookName(),
            $hookable->getName(),
            $hookable->getTarget(),
            $hookable->data ?? $this->getData(),
            $hookable->configuration ?? $this->getConfiguration(),
            $hookable->priority ?? $this->getPriority(),
            $hookable->enabled ?? $this->isEnabled(),
        );
    }
}
