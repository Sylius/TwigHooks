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

namespace Tests\Sylius\TwigHooks\Unit\Profiler;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Profiler\HookableProfile;
use Sylius\TwigHooks\Profiler\HookProfile;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookProfileMotherObject;

final class HookableProfilerTest extends TestCase
{
    /** @var AbstractHookable&MockObject */
    private AbstractHookable $hookable;

    protected function setUp(): void
    {
        $this->hookable = $this->createMock(AbstractHookable::class);
    }

    public function testItReturnsParent(): void
    {
        $hookProfile = HookProfileMotherObject::some();

        $testSubject = $this->createTestSubject($hookProfile, 'some_name');

        self::assertSame($hookProfile, $testSubject->getParent());
    }

    public function testItReturnsName(): void
    {
        $testSubject = $this->createTestSubject(HookProfileMotherObject::some(), 'some_name');

        self::assertSame('some_name', $testSubject->getName());
    }

    public function testItReturnsHookable(): void
    {
        $testSubject = $this->createTestSubject(HookProfileMotherObject::some(), 'some_name');

        self::assertSame($this->hookable, $testSubject->getHookable());
    }

    public function testItAddsChild(): void
    {
        $testSubject = $this->createTestSubject(HookProfileMotherObject::some(), 'some_name');

        self::assertSame([], $testSubject->getChildren());

        $child = HookProfileMotherObject::some();
        $testSubject->addChild($child);

        self::assertSame([$child], $testSubject->getChildren());
    }

    public function testItSetsDuration(): void
    {
        $testSubject = $this->createTestSubject(HookProfileMotherObject::some(), 'some_name');

        self::assertNull($testSubject->getDuration());

        $testSubject->setDuration(1.0);

        self::assertSame(1.0, $testSubject->getDuration());
    }

    /**
     * @param array<HookProfile> $children
     */
    private function createTestSubject(HookProfile $parent, string $name, array $children = []): HookableProfile
    {
        return new HookableProfile($parent, $name, $this->hookable, $children);
    }
}
