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

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Profiler\HookableProfile;
use Sylius\TwigHooks\Profiler\HookProfile;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableProfileMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookProfileMotherObject;

final class HookProfileTest extends TestCase
{
    public function testItReturnsParent(): void
    {
        $parent = HookProfileMotherObject::some();

        $testSubject = $this->createTestSubject(['some_hook'], [], $parent);

        self::assertSame($parent, $testSubject->getParent());
    }

    /**
     * @dataProvider itReturnsNameCases
     *
     * @param array<string> $names
     */
    public function testItReturnsName(array $names, string $expectedName): void
    {
        $testSubject = $this->createTestSubject($names, []);

        self::assertSame($expectedName, $testSubject->getName());
    }

    /**
     * @return array<string, array{array<string>, string}>
     */
    public static function itReturnsNameCases(): array
    {
        return [
            'single hook name' => [
                ['some_hook'],
                'some_hook',
            ],
            'multiple hooks names' => [
                ['some_hook', 'some_other_hook'],
                'some_hook, some_other_hook',
            ],
        ];
    }

    public function testItAddsHookableProfile(): void
    {
        $testSubject = $this->createTestSubject([], []);

        self::assertSame([], $testSubject->getHookablesProfiles());

        $hookableProfile = HookableProfileMotherObject::some();
        $testSubject->addHookableProfile($hookableProfile);

        self::assertSame([$hookableProfile], $testSubject->getHookablesProfiles());
    }

    public function testItSetsDuration(): void
    {
        $testSubject = $this->createTestSubject([], []);

        self::assertNull($testSubject->getDuration());

        $testSubject->setDuration(1.0);

        self::assertSame(1.0, $testSubject->getDuration());
    }

    /**
     * @param array<string> $hooksNames
     * @param array<HookableProfile> $hookablesProfiles
     */
    private function createTestSubject(array $hooksNames, array $hookablesProfiles, ?HookProfile $parent = null): HookProfile
    {
        return new HookProfile($hooksNames, $hookablesProfiles, $parent);
    }
}
