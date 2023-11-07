<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Renderer;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\HookableTemplate;
use Sylius\TwigHooks\Provider\ConfigurationProviderInterface;
use Sylius\TwigHooks\Provider\DataProviderInterface;
use Twig\Environment as Twig;

final class HookableTemplateRenderer implements SupportableHookableRendererInterface
{
    public const HOOKABLE_CONFIGURATION_PARAMETER = 'hookable_configuration';

    public const HOOKABLE_DATA_PARAMETER = 'hookable_data';

    public function __construct(
        private Twig $twig,
        private DataProviderInterface $dataProvider,
        private ConfigurationProviderInterface $configurationProvider,
    ) {
    }

    public function render(AbstractHookable $hookable, array $hookData = []): string
    {
        if (!$this->supports($hookable)) {
            throw new \InvalidArgumentException(
                sprintf('Hookable must be an instance of "%s".', HookableTemplate::class)
            );
        }

        $data = $this->dataProvider->provide($hookable, $hookData);
        $configuration = $this->configurationProvider->provide($hookable);

        return $this->twig->render($hookable->getTarget(), [
            self::HOOKABLE_DATA_PARAMETER => $data,
            self::HOOKABLE_CONFIGURATION_PARAMETER => $configuration,
        ]);
    }

    public function supports(AbstractHookable $hookable): bool
    {
        return is_a($hookable, HookableTemplate::class, true);
    }
}
