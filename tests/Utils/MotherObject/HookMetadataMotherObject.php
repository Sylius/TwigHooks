<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Utils\MotherObject;

use Sylius\TwigHooks\Hook\Metadata\HookMetadata;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

final class HookMetadataMotherObject
{
    public static function some(): HookMetadata
    {
        return new HookMetadata('some_name', new ParameterBag([]));
    }
}
