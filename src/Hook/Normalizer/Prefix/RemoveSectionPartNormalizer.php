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

final class RemoveSectionPartNormalizer implements PrefixNormalizerInterface
{
    /**
     * @param non-empty-string|false $separator
     */
    public function __construct(private readonly string|false $separator)
    {
    }

    public function normalize(string $prefix): string
    {
        if (false === $this->separator) {
            return $prefix;
        }

        $parts = explode('.', $prefix);
        $result = [];

        foreach ($parts as $part) {
            $hookNameExplodedBySectionSeparator = explode($this->separator, $part);

            $result[] = current($hookNameExplodedBySectionSeparator);
        }

        return implode('.', $result);
    }
}
