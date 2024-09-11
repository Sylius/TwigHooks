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

namespace Tests\Sylius\TwigHooks\Unit\Hookable;

use PHPUnit\Framework\TestCase;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

final class HookableTemplateTest extends TestCase
{
    public function testItReturnsItsPriority(): void
    {
        $hookable = HookableTemplateMotherObject::withPriority(10);

        $this->assertSame(10, $hookable->priority());
    }

    public function testItReturnsZeroWhenThePriorityIsNull(): void
    {
        $hookable = HookableTemplateMotherObject::withPriority(null);

        $this->assertSame(0, $hookable->priority());
    }
}
