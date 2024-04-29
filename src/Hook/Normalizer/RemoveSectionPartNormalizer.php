<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Normalizer;

final class RemoveSectionPartNormalizer implements NameNormalizerInterface
{
    /**
     * @param non-empty-string|false $separator
     */
    public function __construct(private readonly string|false $separator)
    {
    }

    public function normalize(string $name): string
    {
        if (false === $this->separator) {
            return $name;
        }

        $parts = explode('.', $name);
        $result = [];

        foreach ($parts as $part) {
            $hookNameExplodedBySectionSeparator = explode($this->separator, $part);

            $result[] = current($hookNameExplodedBySectionSeparator);
        }

        return implode('.', $result);
    }
}
