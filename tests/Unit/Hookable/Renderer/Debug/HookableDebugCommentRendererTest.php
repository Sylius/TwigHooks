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

namespace Tests\Sylius\TwigHooks\Unit\Hookable\Renderer\Debug;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Debug\DebugAwareRendererInterface;
use Sylius\TwigHooks\Hookable\Renderer\Debug\HookableDebugCommentRenderer;
use Sylius\TwigHooks\Hookable\Renderer\HookableRendererInterface;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableMetadataMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

final class HookableDebugCommentRendererTest extends TestCase
{
    /** @var HookableRendererInterface&MockObject */
    private HookableRendererInterface $innerRenderer;

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
        $this->innerRenderer = $this->createMock(HookableRendererInterface::class);
    }

    public function testItAddsDebugCommentsToRenderedHookable(): void
    {
        $hookable = HookableTemplateMotherObject::some();
        $metadata = HookableMetadataMotherObject::some();

        $this->innerRenderer
            ->expects($this->once())
            ->method('render')
            ->with($hookable, $metadata)
            ->willReturn('some-rendered-hookable')
        ;

        $expectedRenderedHookable = <<<HOOKABLE
        <!-- BEGIN HOOKABLE | hook: "some_hook", name: "some_name", template: "some_target", priority: 0 -->
        some-rendered-hookable
        <!--  END HOOKABLE  | hook: "some_hook", name: "some_name", template: "some_target", priority: 0 -->
        HOOKABLE;

        $this->assertSame($expectedRenderedHookable, $this->getTestSubject()->render($hookable, $metadata));
    }

    /**
     * @dataProvider getDebugPrefixAndSuffix
     */
    public function testItAddsDebugCommentsToRenderedHookableWithCustomPrefixAndSuffix(string $prefix, string $suffix): void
    {
        $hookable = HookableTemplateMotherObject::some();
        $metadata = HookableMetadataMotherObject::withContext([
            DebugAwareRendererInterface::CONTEXT_DEBUG_PREFIX => $prefix,
            DebugAwareRendererInterface::CONTEXT_DEBUG_SUFFIX => $suffix,
        ]);

        $this->innerRenderer
            ->expects($this->once())
            ->method('render')
            ->with($hookable, $metadata)
            ->willReturn('some-rendered-hookable')
        ;

        $expectedRenderedHookable = <<<HOOKABLE
        $prefix BEGIN HOOKABLE | hook: "some_hook", name: "some_name", template: "some_target", priority: 0 $suffix
        some-rendered-hookable
        $prefix  END HOOKABLE  | hook: "some_hook", name: "some_name", template: "some_target", priority: 0 $suffix
        HOOKABLE;

        $this->assertSame($expectedRenderedHookable, $this->getTestSubject()->render($hookable, $metadata));
    }

    private function getTestSubject(): HookableDebugCommentRenderer
    {
        return new HookableDebugCommentRenderer($this->innerRenderer);
    }
}
