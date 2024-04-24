<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Registry;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Merger\HookableMergerInterface;
use Sylius\TwigHooks\Registry\HookablesRegistry;
use Tests\Sylius\TwigHooks\Utils\MotherObject\DisabledHookableMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

final class HookablesRegistryTest extends TestCase
{
    /** @var HookableMergerInterface&MockObject */
    private HookableMergerInterface $hookableMerger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hookableMerger = $this->createMock(HookableMergerInterface::class);
    }

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

        $this->hookableMerger
            ->expects($this->exactly(2))
            ->method('merge')
            ->willReturnCallback(static function (AbstractHookable $first, AbstractHookable $second): AbstractHookable {
                return $second;
            })
        ;

        $hookable = $this->getTestSubject([$hookableOne, $hookableTwo, $hookableThree])
            ->getEnabledFor(['some_hook', 'another_hook', 'yet_another_hook'])
        ;

        $this->assertCount(1, $hookable);
        $this->assertSame('some_hook', $hookable[0]->hookName);
        $this->assertSame('hookable_one', $hookable[0]->name);
        $this->assertSame('some_target', $hookable[0]->template);
        $this->assertSame([], $hookable[0]->context);
        $this->assertSame([], $hookable[0]->configuration);
        $this->assertSame(0, $hookable[0]->priority());
    }

    /**
     * @param iterable<AbstractHookable> $hookables
     */
    private function getTestSubject(iterable $hookables): HookablesRegistry
    {
        return new HookablesRegistry($hookables, $this->hookableMerger);
    }

    /**
     * @return AbstractHookable&MockObject
     */
    private function createHookable(string $hookName, string $name, bool $enabled = true): AbstractHookable
    {
        $arguments = ['hookName' => $hookName, 'name' => $name];

        return match ($enabled) {
            false => DisabledHookableMotherObject::with($arguments),
            true => HookableTemplateMotherObject::with($arguments),
        };
    }
}
