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

namespace Tests\Sylius\TwigHooks\Unit\Hook\Renderer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hook\Renderer\HookRenderer;
use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadataFactoryInterface;
use Sylius\TwigHooks\Hookable\Renderer\HookableRendererInterface;
use Sylius\TwigHooks\Provider\ConfigurationProviderInterface;
use Sylius\TwigHooks\Provider\ContextProviderInterface;
use Sylius\TwigHooks\Registry\HookablesRegistry;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

final class HookRendererTest extends TestCase
{
    /** @var HookablesRegistry&MockObject */
    private HookablesRegistry $hookablesRegistry;

    /** @var HookableRendererInterface&MockObject */
    private HookableRendererInterface $hookableRenderer;

    /** @var ContextProviderInterface&MockObject */
    private ContextProviderInterface $contextProvider;

    /** @var ConfigurationProviderInterface&MockObject */
    private ConfigurationProviderInterface $configurationProvider;

    /** @var HookableMetadataFactoryInterface&MockObject */
    private HookableMetadataFactoryInterface $hookableMetadataFactory;

    protected function setUp(): void
    {
        $this->hookablesRegistry = $this->createMock(HookablesRegistry::class);
        $this->hookableRenderer = $this->createMock(HookableRendererInterface::class);
        $this->contextProvider = $this->createMock(ContextProviderInterface::class);
        $this->configurationProvider = $this->createMock(ConfigurationProviderInterface::class);
        $this->hookableMetadataFactory = $this->createMock(HookableMetadataFactoryInterface::class);
    }

    public function testItReturnsRenderedHookables(): void
    {
        $hookableOne = HookableTemplateMotherObject::withName('first_hook');
        $hookableTwo = HookableTemplateMotherObject::withName('second_hook');

        $this->hookablesRegistry->method('getEnabledFor')->willReturn([$hookableOne, $hookableTwo]);
        $this->contextProvider->method('provide')->willReturn([]);
        $this->configurationProvider->method('provide')->willReturn([]);

        $this->hookableRenderer->expects($this->exactly(2))->method('render')->willReturnCallback(
            static fn (AbstractHookable $hookable): string => match ($hookable) {
                $hookableOne => 'hookable_one_rendered',
                $hookableTwo => 'hookable_two_rendered',
            },
        );

        $result = $this->getTestSubject()->render(['hook_name']);
        $expected = <<<RENDER
        hookable_one_rendered
        hookable_two_rendered
        RENDER;

        $this->assertSame($expected, $result);
    }

    public function testItReturnsEmptyStringWhenNoHookablesAreFound(): void
    {
        $this->hookablesRegistry->method('getEnabledFor')->willReturn([]);
        $this->contextProvider->method('provide')->willReturn([]);
        $this->configurationProvider->method('provide')->willReturn([]);

        $result = $this->getTestSubject()->render(['hook_name']);

        $this->assertSame('', $result);
    }

    private function getTestSubject(): HookRenderer
    {
        return new HookRenderer(
            $this->hookablesRegistry,
            $this->hookableRenderer,
            $this->contextProvider,
            $this->configurationProvider,
            $this->hookableMetadataFactory,
        );
    }
}
