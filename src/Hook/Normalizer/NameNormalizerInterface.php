<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Normalizer;

interface NameNormalizerInterface
{
    public function normalize(string $name): string;
}
