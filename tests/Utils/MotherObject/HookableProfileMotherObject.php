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
