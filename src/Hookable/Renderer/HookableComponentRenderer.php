<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Symfony\UX\TwigComponent\ComponentRendererInterface;

final class HookableComponentRenderer implements SupportableHookableRendererInterface
{
    public const HOOKABLE_CONFIGURATION_PARAMETER = 'hookableConfiguration';

    public function __construct(
        private readonly ComponentRendererInterface $componentRenderer,
    ) {
    }

    public function render(AbstractHookable $hookable, HookableMetadata $metadata): string
    {
        if (!$this->supports($hookable)) {
            throw new \InvalidArgumentException(
                sprintf('Hookable must be the "%s" type, but "%s" given.', AbstractHookable::TYPE_COMPONENT, $hookable->getType()),
            );
        }

        $context = $metadata->context->all();
        $configuration = $metadata->configuration->all();

        return $this->componentRenderer->createAndRender($hookable->getTarget(), [
            self::HOOKABLE_CONFIGURATION_PARAMETER => $configuration,
            ...$context,
        ]);
    }

    public function supports(AbstractHookable $hookable): bool
    {
        return $hookable->isComponentType();
    }
}
