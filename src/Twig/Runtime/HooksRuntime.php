<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Twig\Runtime;

use Sylius\TwigHooks\Bag\DataBagInterface;
use Sylius\TwigHooks\Hook\Normalizer\NameNormalizerInterface;
use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Twig\Error\RuntimeError;
use Twig\Extension\RuntimeExtensionInterface;

final class HooksRuntime implements RuntimeExtensionInterface
{
    public const HOOKABLE_METADATA = 'hookable_metadata';

    public function __construct (
        private readonly HookRendererInterface $hookRenderer,
        private readonly NameNormalizerInterface $nameNormalizer,
        private readonly bool $enableAutoprefixing,
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
    public function getHookableContext(array $context): DataBagInterface
    {
        return $this->getHookableMetadata($context)->context;
    }

    /**
     * @param array<string, mixed> $context
     * @throws RuntimeError
     */
    public function getHookableConfiguration(array $context): DataBagInterface
    {
        return $this->getHookableMetadata($context)->configuration;
    }

    /**
     * @param string|array<string> $hookNames
     * @param array<string, mixed> $hookContext
     */
    public function renderHook(
        string|array $hookNames,
        array $hookContext = [],
        ?HookableMetadata $hookableMetadata = null,
        bool $only = false,
    ): string
    {
        $hookNames = is_string($hookNames) ? [$hookNames] : $hookNames;
        $hookNames = array_map([$this->nameNormalizer, 'normalize'], $hookNames);

        $context = $this->getContext($hookContext, $hookableMetadata, $only);
        $prefixes = $this->getPrefixes($hookContext, $hookableMetadata);

        if (false === $this->enableAutoprefixing || [] === $prefixes) {
            return $this->hookRenderer->render($hookNames, $context);
        }

        $prefixedHookNames = [];

        foreach ($hookNames as $hookName) {
            foreach ($prefixes as $prefix) {
                $normalizedPrefix = $this->nameNormalizer->normalize($prefix);
                $prefixedHookNames[] = implode('.', [$normalizedPrefix, $hookName]);
            }
        }

        return $this->hookRenderer->render($prefixedHookNames, $context);
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

    /**
     * @param array<string, mixed> $hookContext
     * @return array<string, mixed>
     */
    private function getContext(array $hookContext, ?HookableMetadata $hookableMetadata, bool $only = false): array
    {
        if ($only) {
            return $hookContext;
        }

        $context = $hookableMetadata?->context->all() ?? [];

        return array_merge($context, $hookContext);
    }
}
