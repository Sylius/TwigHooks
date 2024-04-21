<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Twig\Runtime\HooksRuntime;
use Twig\Environment as Twig;

final class HookableTemplateRenderer implements SupportableHookableRendererInterface
{
    public function __construct(
        private readonly Twig $twig,
    ) {
    }

    public function render(AbstractHookable $hookable, HookableMetadata $metadata): string
    {
        if (!$this->supports($hookable)) {
            throw new \InvalidArgumentException(
                sprintf('Hookable must be the "%s" type, but "%s" given.', AbstractHookable::TYPE_TEMPLATE, $hookable->getType()),
            );
        }

        return $this->twig->render($hookable->getTarget(), [
            HooksRuntime::HOOKABLE_METADATA => $metadata,
        ]);
    }

    public function supports(AbstractHookable $hookable): bool
    {
        return $hookable->isTemplateType();
    }
}
