<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hookable;

use PHPUnit\Framework\TestCase;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

final class HookableTemplateTest extends TestCase
{
    public function testItReturnsItsPriority(): void
    {
        $hookable = HookableTemplateMotherObject::withPriority(10);

        $this->assertSame(10, $hookable->getPriority());
    }

    public function testItReturnsZeroWhenThePriorityIsNull(): void
    {
        $hookable = HookableTemplateMotherObject::withPriority(null);

        $this->assertSame(0, $hookable->getPriority());
    }

    public function testItThrowsAnExceptionWhenOverwritingWithDifferentName(): void
    {
        $hookable = HookableTemplateMotherObject::withName('name');
        $otherHookable = HookableTemplateMotherObject::withName('other-name');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot overwrite hookable with different name. Expected "name", got "other-name".');

        $hookable->overwriteWith($otherHookable);
    }

    public function testItOverwritesTarget(): void
    {
        $hookable = HookableTemplateMotherObject::withTarget('target');
        $otherHookable = HookableTemplateMotherObject::withTarget('other-target');

        $overwrittenHookable = $hookable->overwriteWith($otherHookable);

        $this->assertSame('other-target', $overwrittenHookable->target);
    }

    public function testItMergesContexts(): void
    {
        $hookable = HookableTemplateMotherObject::withContext(['key' => 'value', 'other-key' => 'value']);
        $otherHookable = HookableTemplateMotherObject::withContext(['other-key' => 'other-value', 'another-key' => 'another-value']);

        $overwrittenHookable = $hookable->overwriteWith($otherHookable);

        $this->assertSame(['key' => 'value', 'other-key' => 'other-value', 'another-key' => 'another-value'], $overwrittenHookable->context);
    }

    public function testItMergesConfigurations(): void
    {
        $hookable = HookableTemplateMotherObject::withConfiguration(['key' => 'value', 'other-key' => 'value']);
        $otherHookable = HookableTemplateMotherObject::withConfiguration(['other-key' => 'other-value', 'another-key' => 'another-value']);

        $overwrittenHookable = $hookable->overwriteWith($otherHookable);

        $this->assertSame(['key' => 'value', 'other-key' => 'other-value', 'another-key' => 'another-value'], $overwrittenHookable->configuration);
    }

    public function testItOverwritesPriority(): void
    {
        $hookable = HookableTemplateMotherObject::withPriority(10);
        $otherHookable = HookableTemplateMotherObject::withPriority(20);

        $overwrittenHookable = $hookable->overwriteWith($otherHookable);

        $this->assertSame(20, $overwrittenHookable->getPriority());
    }

    public function testItOverwritesEnabled(): void
    {
        $hookable = HookableTemplateMotherObject::enabled();
        $otherHookable = HookableTemplateMotherObject::disabled();

        $overwrittenHookable = $hookable->overwriteWith($otherHookable);

        $this->assertFalse($overwrittenHookable->isEnabled());
    }
}
