<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hookable;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\BaseHookable;
use Tests\Sylius\TwigHooks\Utils\MotherObject\BaseHookableMotherObject;

final class BaseHookableTest extends TestCase
{
    public function testItReturnsHookName(): void
    {
        $testSubject = $this->getTestSubject();

        $this->assertSame('some_hook', $testSubject->getHookName());
    }

    public function testItReturnsName(): void
    {
        $testSubject = $this->getTestSubject();

        $this->assertSame('some_name', $testSubject->getName());
    }

    public function testItReturnsTarget(): void
    {
        $testSubject = $this->getTestSubject();

        $this->assertSame('some_target', $testSubject->getTarget());
    }

    public function testItReturnsData(): void
    {
        $testSubject = $this->getTestSubject();

        $this->assertSame([], $testSubject->getData());
    }

    public function testItReturnsConfiguration(): void
    {
        $testSubject = $this->getTestSubject();

        $this->assertSame([], $testSubject->getConfiguration());
    }

    public function testItReturnsPriority(): void
    {
        $testSubject = $this->getTestSubject();

        $this->assertSame(0, $testSubject->getPriority());
    }

    public function testItReturnsEnabled(): void
    {
        $testSubject = $this->getTestSubject();

        $this->assertTrue($testSubject->isEnabled());
    }

    public function testItThrowsAnExceptionWhenTryingToOverrideHookableWithDifferentName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Hookable cannot be overwritten with different name.');

        $testSubject = $this->getTestSubject();

        $testSubject->overwriteWith(BaseHookableMotherObject::withName('some_other_name'));
    }

    public function testItOverwritesHookableWithGivenHookable(): void
    {
        $hookableToBeOverwritten = new BaseHookable(
            'some_hook',
            'some_name',
            'template',
            'some_target',
            ['some_data' => 'yes', 'another_data' => 'no'],
            ['title' => 'King', 'name' => 'Arthur'],
            50,
            true,
        );
        $hookableToOverwrite = new BaseHookable(
            'some_hook',
            'some_name',
            'component',
            'some_other_target',
            ['another_data' => 'yes', 'another_other_data' => 'no'],
            ['title' => 'Queen'],
            100,
            false,
        );

        $overwrittenHookable = $hookableToBeOverwritten->overwriteWith($hookableToOverwrite);

        $this->assertSame('some_hook', $overwrittenHookable->getHookName());
        $this->assertSame('some_name', $overwrittenHookable->getName());
        $this->assertSame('component', $overwrittenHookable->getType());
        $this->assertSame('some_other_target', $overwrittenHookable->getTarget());
        $this->assertSame(['some_data' => 'yes', 'another_data' => 'yes', 'another_other_data' => 'no'], $overwrittenHookable->getData());
        $this->assertSame(['title' => 'Queen', 'name' => 'Arthur'], $overwrittenHookable->getConfiguration());
        $this->assertSame(100, $overwrittenHookable->getPriority());
        $this->assertFalse($overwrittenHookable->isEnabled());
    }

    public function testItAllowsToOverwriteHookableWithAnotherType(): void
    {
        $testSubject = $this->getTestSubject();

        $this->assertSame('template', $testSubject->getType());

        $overwrittenTestSubject = $testSubject->overwriteWith(BaseHookableMotherObject::withType('component'));

        $this->assertSame('component', $overwrittenTestSubject->getType());
    }

    public function testItReturnsItsTypeName(): void
    {
        $testSubject = $this->getTestSubject();

        $this->assertSame('template', $testSubject->getType());
    }

    private function getTestSubject(): BaseHookable
    {
        return BaseHookableMotherObject::some();
    }
}
