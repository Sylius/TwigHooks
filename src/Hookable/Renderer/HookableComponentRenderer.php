<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Provider\ConfigurationProviderInterface;
use Sylius\TwigHooks\Provider\DataProviderInterface;
use Symfony\UX\TwigComponent\ComponentRendererInterface;

final class HookableComponentRenderer implements SupportableHookableRendererInterface
{
    public const HOOKABLE_CONFIGURATION_PARAMETER = 'hookableConfiguration';

    public const HOOKABLE_DATA_PARAMETER = 'hookableData';

    public function __construct(
        private ComponentRendererInterface $componentRenderer,
        private DataProviderInterface $dataProvider,
        private ConfigurationProviderInterface $configurationProvider,
    ) {
    }

    public function render(AbstractHookable $hookable, array $hookData = []): string
    {
        if (!$this->supports($hookable)) {
            throw new \InvalidArgumentException(
                sprintf('Hookable must be the "%s" type, but "%s" given.', AbstractHookable::TYPE_COMPONENT, $hookable->getType()),
            );
        }

        $data = $this->dataProvider->provide($hookable, $hookData);
        $configuration = $this->configurationProvider->provide($hookable);

        return $this->componentRenderer->createAndRender($hookable->getTarget(), [
            self::HOOKABLE_CONFIGURATION_PARAMETER => $configuration,
            ...$data,
        ]);
    }

    public function supports(AbstractHookable $hookable): bool
    {
        return $hookable->isComponentType();
    }
}
