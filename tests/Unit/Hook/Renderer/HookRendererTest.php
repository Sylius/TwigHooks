<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hook\Renderer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hook\Renderer\HookRenderer;
use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Renderer\HookableRendererInterface;
use Sylius\TwigHooks\Registry\HookablesRegistry;

final class HookRendererTest extends TestCase
{
    /** @var HookablesRegistry&MockObject */
    private HookablesRegistry $hookablesRegistry;

    /** @var HookableRendererInterface&MockObject */
    private HookableRendererInterface $hookableRenderer;

    protected function setUp(): void
    {
        $this->hookablesRegistry = $this->createMock(HookablesRegistry::class);
        $this->hookableRenderer = $this->createMock(HookableRendererInterface::class);
    }

    public function testItRendersHookablesForGivenHookName(): void
    {
        $hookableOne = $this->createMock(AbstractHookable::class);
        $hookableTwo = $this->createMock(AbstractHookable::class);

        $this->hookablesRegistry->method('getEnabledFor')->willReturn([$hookableOne, $hookableTwo]);

        $this->hookableRenderer->expects($this->exactly(2))->method('render')->willReturnCallback(
            static fn (AbstractHookable $hookable): string => match ($hookable) {
                $hookableOne => 'hookable_one_rendered',
                $hookableTwo => 'hookable_two_rendered',
            }
        );

        $result = $this->getTestSubject()->render('hook_name');
        $expected = <<<RENDER
        hookable_one_rendered
        hookable_two_rendered
        RENDER;

        $this->assertSame($expected, $result);
    }

    private function getTestSubject(): HookRenderer
    {
        return new HookRenderer($this->hookablesRegistry, $this->hookableRenderer);
    }
}
