<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hookable;

use PHPUnit\Framework\TestCase;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableComponentMotherObject;

final class HookableComponentTest extends TestCase
{
    public function testItReturnsItsPriority(): void
    {
        $hookable = HookableComponentMotherObject::withPriority(10);

        $this->assertSame(10, $hookable->priority());
    }

    public function testItReturnsZeroWhenThePriorityIsNull(): void
    {
        $hookable = HookableComponentMotherObject::withPriority(null);

        $this->assertSame(0, $hookable->priority());
    }
}
