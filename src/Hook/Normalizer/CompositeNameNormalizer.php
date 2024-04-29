<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Normalizer;

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
