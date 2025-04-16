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

namespace Tests\Sylius\TwigHooks\Unit\Hook\Renderer\Debug;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Debug\DebugAwareRendererInterface;
use Sylius\TwigHooks\Hook\Renderer\Debug\HookDebugCommentRenderer;
use Sylius\TwigHooks\Hook\Renderer\HookRendererInterface;

final class HookDebugCommentRendererTest extends TestCase
{
    /** @var HookRendererInterface&MockObject */
    private HookRendererInterface $innerRenderer;

    public static function getDebugPrefixAndSuffix(): iterable
    {
        yield 'JavaScript one line' => ['//', ''];
        yield 'JavaScript block' => ['/*', '*/'];
        yield 'Yaml' => ['#', ''];
        yield 'Ini' => [';', ''];
        yield 'Custom HTML' => ['<!-- CUSTOM', 'END CUSTOM -->'];
    }

    protected function setUp(): void
    {
        $this->innerRenderer = $this->createMock(HookRendererInterface::class);
    }

    public function testItAddsDebugCommentForSingleHookName(): void
    {
        $this->innerRenderer
            ->expects($this->once())
            ->method('render')
            ->with(['some-hook'], [])
            ->willReturn('some-rendered-hook')
        ;

        $expectedRenderedHookable = <<<HOOK
        <!-- BEGIN HOOK | name: "some-hook" -->
        some-rendered-hook
        <!--  END HOOK  | name: "some-hook" -->
        HOOK;

        $this->assertSame($expectedRenderedHookable, $this->getTestSubject()->render(['some-hook'], []));
    }

    public function testIdAddsDebugCommentForMultipleHooksNames(): void
    {
        $this->innerRenderer
            ->expects($this->once())
            ->method('render')
            ->with(['some-hook', 'another-hook'], [])
            ->willReturn('some-rendered-hook')
        ;

        $expectedRenderedHookable = <<<HOOK
        <!-- BEGIN HOOK | name: "some-hook, another-hook" -->
        some-rendered-hook
        <!--  END HOOK  | name: "some-hook, another-hook" -->
        HOOK;

        $this->assertSame($expectedRenderedHookable, $this->getTestSubject()->render(['some-hook', 'another-hook'], []));
    }

    /**
     * @dataProvider getDebugPrefixAndSuffix
     */
    public function testItAddsDebugCommentForSingleHookNameWithCustomPrefixAndSuffix(string $prefix, string $suffix): void
    {
        $this->innerRenderer
            ->expects($this->once())
            ->method('render')
            ->with(['some-hook'], [
                DebugAwareRendererInterface::CONTEXT_DEBUG_PREFIX => $prefix,
                DebugAwareRendererInterface::CONTEXT_DEBUG_SUFFIX => $suffix,
            ])
            ->willReturn('some-rendered-hook')
        ;

        $expectedRenderedHookable = <<<HOOK
        $prefix BEGIN HOOK | name: "some-hook" $suffix
        some-rendered-hook
        $prefix  END HOOK  | name: "some-hook" $suffix
        HOOK;

        $this->assertSame($expectedRenderedHookable, $this->getTestSubject()->render(['some-hook'], [
            DebugAwareRendererInterface::CONTEXT_DEBUG_PREFIX => $prefix,
            DebugAwareRendererInterface::CONTEXT_DEBUG_SUFFIX => $suffix,
        ]));
    }

    private function getTestSubject(): HookDebugCommentRenderer
    {
        return new HookDebugCommentRenderer($this->innerRenderer);
    }
}
