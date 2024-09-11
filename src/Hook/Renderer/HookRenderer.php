<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Renderer;

use Sylius\TwigHooks\Bag\DataBag;
use Sylius\TwigHooks\Bag\ScalarDataBag;
use Sylius\TwigHooks\Hook\Metadata\HookMetadata;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadataFactoryInterface;
use Sylius\TwigHooks\Hookable\Renderer\HookableRendererInterface;
use Sylius\TwigHooks\Provider\ConfigurationProviderInterface;
use Sylius\TwigHooks\Provider\ContextProviderInterface;
use Sylius\TwigHooks\Registry\HookablesRegistry;

final class HookRenderer implements HookRendererInterface
{
    public function __construct(
        private readonly HookablesRegistry $hookablesRegistry,
        private readonly HookableRendererInterface $compositeHookableRenderer,
        private readonly ContextProviderInterface $contextProvider,
        private readonly ConfigurationProviderInterface $configurationProvider,
        private readonly HookableMetadataFactoryInterface $hookableMetadataFactory,
    ) {
    }

    /**
     * @param array<string> $hookNames
     * @param array<string, mixed> $hookContext
     */
    public function render(array $hookNames, array $hookContext = []): string
    {
        $hookables = $this->hookablesRegistry->getEnabledFor($hookNames);
        $renderedHookables = [];

        foreach ($hookables as $hookable) {
            $hookMetadata = new HookMetadata($hookable->hookName, new DataBag($hookContext));

            $context = $this->contextProvider->provide($hookable, $hookContext);
            $configuration = $this->configurationProvider->provide($hookable);

            $hookableMetadata = $this->hookableMetadataFactory->create(
                $hookMetadata,
                new DataBag($context),
                new ScalarDataBag($configuration),
                $hookNames,
            );

            $renderedHookables[] = $this->compositeHookableRenderer->render($hookable, $hookableMetadata);
        }

        return implode(\PHP_EOL, $renderedHookables);
    }
}
