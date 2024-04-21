<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Hookable\Renderer\Exception\NoSupportedRendererException;

final class CompositeHookableRenderer implements HookableRendererInterface
{
    /** @var array<SupportableHookableRendererInterface> */
    private array $renderers = [];

    /**
     * @param iterable<object> $renderers
     */
    public function __construct (iterable $renderers)
    {
        foreach ($renderers as $renderer) {
            if (!$renderer instanceof SupportableHookableRendererInterface) {
                throw new \InvalidArgumentException(
                    sprintf('Hookable renderer must be an instance of "%s".', SupportableHookableRendererInterface::class)
                );
            }

            $this->renderers[] = $renderer;
        }
    }

    public function render(AbstractHookable $hookable, HookableMetadata $metadata): string
    {
        foreach ($this->renderers as $renderer) {
            if ($renderer->supports($hookable)) {
                return $renderer->render($hookable, $metadata);
            }
        }

        throw new NoSupportedRendererException($hookable->getHookName(), $hookable->getName());
    }
}
