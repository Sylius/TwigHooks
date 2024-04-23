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

        $this->assertSame(10, $hookable->getPriority());
    }

    public function testItReturnsZeroWhenThePriorityIsNull(): void
    {
        $hookable = HookableComponentMotherObject::withPriority(null);

        $this->assertSame(0, $hookable->getPriority());
    }

    public function testItThrowsAnExceptionWhenOverwritingWithDifferentName(): void
    {
        $hookable = HookableComponentMotherObject::withName('name');
        $otherHookable = HookableComponentMotherObject::withName('other-name');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot overwrite hookable with different name. Expected "name", got "other-name".');

        $hookable->overwriteWith($otherHookable);
    }

    public function testItOverwritesTarget(): void
    {
        $hookable = HookableComponentMotherObject::withTarget('target');
        $otherHookable = HookableComponentMotherObject::withTarget('other-target');

        $overwrittenHookable = $hookable->overwriteWith($otherHookable);

        $this->assertSame('other-target', $overwrittenHookable->target);
    }

    public function testItMergesProps(): void
    {
        $hookable = HookableComponentMotherObject::withProps(['key' => 'value', 'other-key' => 'value']);
        $otherHookable = HookableComponentMotherObject::withProps(['other-key' => 'other-value', 'another-key' => 'another-value']);

        $overwrittenHookable = $hookable->overwriteWith($otherHookable);

        $this->assertSame(['key' => 'value', 'other-key' => 'other-value', 'another-key' => 'another-value'], $overwrittenHookable->props);
    }

    public function testItMergesContexts(): void
    {
        $hookable = HookableComponentMotherObject::withContext(['key' => 'value', 'other-key' => 'value']);
        $otherHookable = HookableComponentMotherObject::withContext(['other-key' => 'other-value', 'another-key' => 'another-value']);

        $overwrittenHookable = $hookable->overwriteWith($otherHookable);

        $this->assertSame(['key' => 'value', 'other-key' => 'other-value', 'another-key' => 'another-value'], $overwrittenHookable->context);
    }

    public function testItMergesConfigurations(): void
    {
        $hookable = HookableComponentMotherObject::withConfiguration(['key' => 'value', 'other-key' => 'value']);
        $otherHookable = HookableComponentMotherObject::withConfiguration(['other-key' => 'other-value', 'another-key' => 'another-value']);

        $overwrittenHookable = $hookable->overwriteWith($otherHookable);

        $this->assertSame(['key' => 'value', 'other-key' => 'other-value', 'another-key' => 'another-value'], $overwrittenHookable->configuration);
    }

    public function testItOverwritesPriority(): void
    {
        $hookable = HookableComponentMotherObject::withPriority(10);
        $otherHookable = HookableComponentMotherObject::withPriority(20);

        $overwrittenHookable = $hookable->overwriteWith($otherHookable);

        $this->assertSame(20, $overwrittenHookable->getPriority());
    }

    public function testItOverwritesEnabled(): void
    {
        $hookable = HookableComponentMotherObject::enabled();
        $otherHookable = HookableComponentMotherObject::disabled();

        $overwrittenHookable = $hookable->overwriteWith($otherHookable);

        $this->assertFalse($overwrittenHookable->isEnabled());
    }
}
