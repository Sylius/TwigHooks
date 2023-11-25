<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Utils\MotherObject;

use Sylius\TwigHooks\Profiler\HookProfile;

final class HookProfileMotherObject
{
    public static function some(): HookProfile
    {
        return new HookProfile(['some_name'], [], null);
    }
}
