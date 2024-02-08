<?php

declare(strict_types=1);

namespace Sylius\TwigHooks;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class TwigHooksBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
