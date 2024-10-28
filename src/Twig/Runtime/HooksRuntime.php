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

namespace Sylius\TwigHooks\Twig\Runtime;

use Sylius\TwigHooks\Bag\DataBagInterface;
use Sylius\TwigHooks\Bag\ScalarDataBagInterface;
use Sylius\TwigHooks\Hook\Normalizer\Name\NameNormalizerInterface;
use Sylius\TwigHooks\Hook\Normalizer\Prefix\PrefixNormalizerInterface;
use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Twig\Error\RuntimeError;
use Twig\Extension\RuntimeExtensionInterface;
use Webmozart\Assert\Assert;

final class HooksRuntime implements RuntimeExtensionInterface
{
    public const HOOKABLE_METADATA = 'hookable_metadata';

    public function __construct(
        private readonly HookRendererInterface $hookRenderer,
        private readonly NameNormalizerInterface $nameNormalizer,
        private readonly PrefixNormalizerInterface $prefixNormalizer,
        private readonly bool $enableAutoprefixing,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     *
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
     *
     * @throws RuntimeError
     */
    public function getHookableContext(array $context): DataBagInterface
    {
        return $this->getHookableMetadata($context)->context;
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws RuntimeError
     */
    public function getHookableConfiguration(array $context): ScalarDataBagInterface
    {
        return $this->getHookableMetadata($context)->configuration;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function isHookable(array $context): bool
    {
        return array_key_exists(self::HOOKABLE_METADATA, $context) && $context[self::HOOKABLE_METADATA] instanceof HookableMetadata;
    }

    /**
     * @param string|array<string> $hookNames
     * @param array<string, mixed> $twigVars
     * @param array<string, mixed> $hookContext
     */
    public function renderHook(
        string|array $hookNames,
        array $hookContext = [],
        array $twigVars = [],
        bool $only = false,
    ): string {
        $hookNames = is_string($hookNames) ? [$hookNames] : $hookNames;
        $hookNames = array_map([$this->nameNormalizer, 'normalize'], $hookNames);

        $hookableMetadata = $twigVars[self::HOOKABLE_METADATA] ?? null;
        Assert::nullOrIsInstanceOf($hookableMetadata, HookableMetadata::class);
        unset($twigVars[self::HOOKABLE_METADATA]);

        $context = $this->getContext($hookContext, $twigVars, $hookableMetadata, $only);
        $prefixes = $this->getPrefixes($hookContext, $hookableMetadata);

        if (false === $this->enableAutoprefixing || [] === $prefixes) {
            return $this->hookRenderer->render($hookNames, $context);
        }

        $prefixedHookNames = [];

        foreach ($hookNames as $hookName) {
            foreach ($prefixes as $prefix) {
                $format = str_starts_with($hookName, '#') ? '%s%s' : '%s.%s';
                $prefixedHookNames[] = sprintf($format, $prefix, $hookName);
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

        $prefixes = array_map([$this->prefixNormalizer, 'normalize'], $prefixes);

        return $prefixes;
    }

    /**
     * @param array<string, mixed> $hookContext
     * @param array<string, mixed> $twigVars
     *
     * @return array<string, mixed>
     */
    private function getContext(array $hookContext, array $twigVars, ?HookableMetadata $hookableMetadata, bool $only = false): array
    {
        if ($only) {
            return $hookContext;
        }

        $context = $hookableMetadata?->context->all() ?? [];

        return array_merge($twigVars, $context, $hookContext);
    }
}
