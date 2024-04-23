<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Provider\Exception\InvalidExpressionException;
use Sylius\TwigHooks\Provider\PropsProviderInterface;
use Symfony\UX\TwigComponent\ComponentRendererInterface;

final class HookableComponentRenderer implements SupportableHookableRendererInterface
{
    public const HOOKABLE_METADATA_PARAMETER = 'hookableMetadata';

    public function __construct(
        private readonly PropsProviderInterface $propsProvider,
        private readonly ComponentRendererInterface $componentRenderer,
    ) {
    }

    /**
     * @param HookableComponent $hookable
     * @throws InvalidExpressionException
     */
    public function render(AbstractHookable $hookable, HookableMetadata $metadata): string
    {
        if (!$this->supports($hookable)) {
            throw new \InvalidArgumentException(
                sprintf('Hookable must be the "%s" type, but "%s" given.', HookableComponent::TYPE_NAME, $hookable->getType()),
            );
        }

        $props = $this->propsProvider->provide($hookable, $metadata);

        return $this->componentRenderer->createAndRender($hookable->target, [
            self::HOOKABLE_METADATA_PARAMETER => $metadata,
            ...$props,
        ]);
    }

    public function supports(AbstractHookable $hookable): bool
    {
        return is_a($hookable, HookableComponent::class, true);
    }
}
