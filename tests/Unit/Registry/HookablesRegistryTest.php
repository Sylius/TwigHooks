<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Registry;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Registry\HookablesRegistry;

final class HookablesRegistryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenAtLeastOneElementIsNotAnAbstractHookable(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('All elements must be an instance of "%s".', AbstractHookable::class));

        $this->getTestSubject([new \stdClass()]);
    }

    public function testItReturnsEnabledHookables(): void
    {
        $hookableOne = $this->createHookable('some_hook', 'hookable_one');
        $hookableTwo = $this->createHookable('some_hook', 'hookable_two', false);
        $hookableThree = $this->createHookable('another_hook', 'hookable_three');

        $testSubject = $this->getTestSubject([$hookableOne, $hookableTwo, $hookableThree]);

        $this->assertCount(1, $testSubject->getEnabledFor('some_hook'));
        $this->assertContains($hookableOne, $testSubject->getEnabledFor('some_hook'));

        $this->assertCount(2, $testSubject->getEnabledFor(['some_hook', 'another_hook']));
        $this->assertContains($hookableOne, $testSubject->getEnabledFor(['some_hook', 'another_hook']));
        $this->assertContains($hookableThree, $testSubject->getEnabledFor(['some_hook', 'another_hook']));
    }

    public function testItMergesHookablesWithTheSameName(): void
    {
        $hookableOne = $this->createHookable('some_hook', 'hookable_one');
        $hookableTwo = $this->createHookable('another_hook', 'hookable_one');

        $hookableThree = $this->createHookable('yet_another_hook', 'hookable_one', false);
        $hookableThree->expects($this->exactly(2))->method('overwriteWith')->willReturnCallback(
            fn (AbstractHookable $hookable) => match ($hookable) {
                $hookableTwo, $hookableOne => $hookableThree,
            }
        );

        $this->getTestSubject([$hookableOne, $hookableTwo, $hookableThree])
            ->getEnabledFor(['some_hook', 'another_hook', 'yet_another_hook'])
        ;
    }

    /**
     * @param iterable<AbstractHookable> $hookables
     */
    private function getTestSubject(iterable $hookables): HookablesRegistry
    {
        return new HookablesRegistry($hookables);
    }

    /**
     * @return AbstractHookable&MockObject
     */
    private function createHookable(string $hookName, string $name, bool $enabled = true): AbstractHookable
    {
        $hookable = $this->createMock(AbstractHookable::class);
        $hookable->method('getHookName')->willReturn($hookName);
        $hookable->method('getName')->willReturn($name);
        $hookable->method('isEnabled')->willReturn($enabled);

        return $hookable;
    }
}
