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

namespace Sylius\TwigHooks\Hook\Normalizer\Name;

final class CompositeNameNormalizer implements NameNormalizerInterface
{
    /** @var array<NameNormalizerInterface> */
    private readonly array $nameNormalizers;

    /**
     * @param iterable<NameNormalizerInterface> $nameNormalizers
     */
    public function __construct(iterable $nameNormalizers)
    {
        $this->nameNormalizers = $nameNormalizers instanceof \Traversable ? iterator_to_array($nameNormalizers) : $nameNormalizers;
    }

    public function normalize(string $name): string
    {
        $normalizedHookName = $name;

        foreach ($this->nameNormalizers as $nameNormalizer) {
            $normalizedHookName = $nameNormalizer->normalize($normalizedHookName);
        }

        return $normalizedHookName;
    }
}
