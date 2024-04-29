<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\Normalizer\Name;

interface NameNormalizerInterface
{
    public function normalize(string $name): string;
}
