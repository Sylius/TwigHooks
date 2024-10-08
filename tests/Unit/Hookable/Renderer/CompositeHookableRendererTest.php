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

namespace Tests\Sylius\TwigHooks\Unit\Hookable\Renderer;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Hookable\Renderer\CompositeHookableRenderer;
use Sylius\TwigHooks\Hookable\Renderer\SupportableHookableRendererInterface;

final class CompositeHookableRendererTest extends TestCase
{
    public function testItThrowsAnExceptionWhenAtLeastRendererIsNotSupportableHookableRenderer(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Hookable renderer must be an instance of "Sylius\TwigHooks\Hookable\Renderer\SupportableHookableRendererInterface".');

        $this->getTestSubject([new \stdClass()]);
    }

    public function testItRendersWithUsingFirstSupportedRenderer(): void
    {
        $nonSupportingRenderer = $this->createSupportableHookableRenderer(false, 'non-supporting-renderer');
        $supportingRenderer = $this->createSupportableHookableRenderer(true, 'supporting-renderer');

        $hookable = $this->createMock(AbstractHookable::class);
        $metadata = $this->createMock(HookableMetadata::class);

        $hookableRenderer = $this->getTestSubject([$nonSupportingRenderer, $supportingRenderer]);
        $result = $hookableRenderer->render($hookable, $metadata);

        $this->assertSame('supporting-renderer', $result);
    }

    /**
     * @param iterable<SupportableHookableRendererInterface> $renderers
     */
    private function getTestSubject(iterable $renderers = []): CompositeHookableRenderer
    {
        return new CompositeHookableRenderer($renderers);
    }

    private function createSupportableHookableRenderer(bool $supports, string $result = ''): SupportableHookableRendererInterface
    {
        $renderer = $this->createMock(SupportableHookableRendererInterface::class);
        $renderer->method('supports')->willReturn($supports);
        $renderer->method('render')->willReturn($result);

        return $renderer;
    }
}
