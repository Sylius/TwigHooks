<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\HookableTemplate;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Twig\Runtime\HooksRuntime;
use Twig\Environment as Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class HookableTemplateRenderer implements SupportableHookableRendererInterface
{
    public function __construct(
        private readonly Twig $twig,
    ) {
    }

    /**
     * @param HookableTemplate $hookable
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(AbstractHookable $hookable, HookableMetadata $metadata): string
    {
        if (!$this->supports($hookable)) {
            throw new \InvalidArgumentException(
                sprintf('Hookable must be the "%s", but "%s" given.', HookableTemplate::class, get_class($hookable)),
            );
        }

        return $this->twig->render($hookable->template, [
            HooksRuntime::HOOKABLE_METADATA => $metadata,
        ]);
    }

    public function supports(AbstractHookable $hookable): bool
    {
        return is_a($hookable, HookableTemplate::class, true);
    }
}
