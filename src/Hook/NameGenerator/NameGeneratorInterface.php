<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hook\NameGenerator;

interface NameGeneratorInterface
{
    public function generate(string $input, string ...$parts): string;
}
