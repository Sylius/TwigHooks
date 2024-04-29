<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Normalizer\Prefix;

interface PrefixNormalizerInterface
{
    public function normalize(string $prefix): string;
}
