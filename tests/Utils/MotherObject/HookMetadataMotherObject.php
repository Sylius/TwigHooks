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

use Sylius\TwigHooks\Bag\DataBag;
use Sylius\TwigHooks\Hook\Metadata\HookMetadata;

final class HookMetadataMotherObject
{
    public static function some(): HookMetadata
    {
        return new HookMetadata('some_name', new DataBag([]));
    }
}
