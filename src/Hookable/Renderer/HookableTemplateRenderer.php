<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\HookableTemplate;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Hookable\Renderer\Exception\HookRenderException;
use Sylius\TwigHooks\Twig\Runtime\HooksRuntime;
use Twig\Environment as Twig;

final class HookableTemplateRenderer implements SupportableHookableRendererInterface
{
    public function __construct(
        private readonly Twig $twig,
    ) {
    }

    /**
     * @param HookableTemplate $hookable
     */
    public function render(AbstractHookable $hookable, HookableMetadata $metadata): string
    {
        if (!$this->supports($hookable)) {
            throw new \InvalidArgumentException(
                sprintf('Hookable must be the "%s", but "%s" given.', HookableTemplate::class, get_class($hookable)),
            );
        }

        try {
            return $this->twig->render($hookable->template, [
                HooksRuntime::HOOKABLE_METADATA => $metadata,
            ]);
        } catch (\Throwable $exception) {
            throw new HookRenderException(
                sprintf(
                    'An error occurred during rendering the "%s" hook in the "%s" hookable. Original error: %s',
                    $hookable->name,
                    $hookable->hookName,
                    $exception->getMessage(),
                ),
            );
        }
    }

    public function supports(AbstractHookable $hookable): bool
    {
        return is_a($hookable, HookableTemplate::class, true);
    }
}
