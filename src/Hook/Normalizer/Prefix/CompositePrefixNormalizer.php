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

namespace Sylius\TwigHooks\Hook\Normalizer\Prefix;

final class CompositePrefixNormalizer implements PrefixNormalizerInterface
{
    /** @var array<PrefixNormalizerInterface> */
    private readonly array $prefixNormalizers;

    /**
     * @param iterable<PrefixNormalizerInterface> $prefixNormalizers
     */
    public function __construct(iterable $prefixNormalizers)
    {
        $this->prefixNormalizers = $prefixNormalizers instanceof \Traversable ? iterator_to_array($prefixNormalizers) : $prefixNormalizers;
    }

    public function normalize(string $prefix): string
    {
        $normalizedPrefix = $prefix;

        foreach ($this->prefixNormalizers as $prefixNormalizer) {
            $normalizedPrefix = $prefixNormalizer->normalize($normalizedPrefix);
        }

        return $normalizedPrefix;
    }
}
