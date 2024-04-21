<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Twig\Runtime;

use Sylius\TwigHooks\Hook\NameGenerator\NameGeneratorInterface;
use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Error\RuntimeError;
use Twig\Extension\RuntimeExtensionInterface;

final class HooksRuntime implements RuntimeExtensionInterface
{
    public const HOOKABLE_METADATA = 'hookable_metadata';

    /** @deprecated  */
    public const HOOKABLE_CONFIGURATION_PARAMETER = 'hookable_configuration';

    /** @deprecated  */
    public const HOOKABLE_DATA_PARAMETER = 'hookable_data';

    public function __construct (
        private readonly HookRendererInterface $hookRenderer,
        private readonly NameGeneratorInterface $nameGenerator,
        private readonly bool $enableAutoprefixing = true,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     * @throws RuntimeError
     */
    public function getHookableMetadata(array $context): HookableMetadata
    {
        $hookableMetadata = $context[self::HOOKABLE_METADATA] ?? null;

        if (!$hookableMetadata instanceof HookableMetadata) {
            throw new RuntimeError('Trying to access hookable context inside a non-hookable.');
        }

        return $hookableMetadata;
    }

    /**
     * @param array<string, mixed> $context
     * @throws RuntimeError
     */
    public function getHookableContext(array $context): ParameterBagInterface
    {
        return $this->getHookableMetadata($context)->context;
    }

    /**
     * @param array<string, mixed> $context
     * @throws RuntimeError
     */
    public function getHookableConfiguration(array $context): ParameterBagInterface
    {
        return $this->getHookableMetadata($context)->configuration;
    }

    /**
     * @param string|array<string> $hookNames
     * @param array<string, mixed> $hookContext
     */
    public function renderHook(string|array $hookNames, array $hookContext = [], ?HookableMetadata $hookableMetadata = null): string
    {
        $hookNames = is_string($hookNames) ? [$hookNames] : $hookNames;

        $prefixes = $this->getPrefixes($hookContext, $hookableMetadata);

        if (false === $this->enableAutoprefixing || [] === $prefixes) {
            $hookContext['_prefixes'] = $hookNames;

            return $this->hookRenderer->render($hookNames, $hookContext);
        }

        $prefixedHookNames = [];

        foreach ($hookNames as $hookName) {
            foreach ($prefixes as $prefix) {
                $prefixedHookNames[] = $this->nameGenerator->generate($prefix, $hookName);
            }
        }

        $hookContext['_prefixes'] = $prefixedHookNames;

        return $this->hookRenderer->render($prefixedHookNames, $hookContext);
    }

    /**
     * @param array<string, mixed> $hookContext
     *
     * @return array<string>
     */
    private function getPrefixes(array $hookContext, ?HookableMetadata $hookableMetadata): array
    {
        $prefixes = [];

        if ($hookableMetadata !== null && $hookableMetadata->hasPrefixes()) {
            $prefixes = $hookableMetadata->prefixes;
        }

        if (array_key_exists('_prefixes', $hookContext)) {
            $prefixes = $hookContext['_prefixes'];
        }

        return $prefixes;
    }
}
