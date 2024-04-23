<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Utils\MotherObject;

use Sylius\TwigHooks\Profiler\HookableProfile;

final class HookableProfileMotherObject
{
    public static function some(): HookableProfile
    {
        return new HookableProfile(
            HookProfileMotherObject::some(),
            'some_name',
            HookableTemplateMotherObject::some(),
            [],
        );
    }
}
