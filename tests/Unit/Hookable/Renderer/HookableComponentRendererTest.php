<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hookable\Renderer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Hookable\Renderer\HookableComponentRenderer;
use Sylius\TwigHooks\Provider\PropsProviderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\UX\TwigComponent\ComponentRendererInterface;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableComponentMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableMetadataMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

final class HookableComponentRendererTest extends TestCase
{
    /** @var PropsProviderInterface&MockObject */
    private PropsProviderInterface $propsProvider;

    /** @var ComponentRendererInterface&MockObject */
    private ComponentRendererInterface $componentRenderer;

    protected function setUp(): void
    {
        $this->propsProvider = $this->createMock(PropsProviderInterface::class);
        $this->componentRenderer = $this->createMock(ComponentRendererInterface::class);
    }

    public function testItSupportsOnlyHookableComponents(): void
    {
        $hookableTemplate = HookableTemplateMotherObject::some();
        $hookableComponent = HookableComponentMotherObject::some();

        $this->assertTrue($this->getTestSubject()->supports($hookableComponent));
        $this->assertFalse($this->getTestSubject()->supports($hookableTemplate));
    }

    public function testItThrowsAnExceptionWhenTryingToRenderUnsupportedHookable(): void
    {
        $hookableTemplate = HookableTemplateMotherObject::some();
        $metadata = $this->createMock(HookableMetadata::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Hookable must be the "component" type, but "template" given.');

        $this->getTestSubject()->render($hookableTemplate, $metadata);
    }

    public function testItRendersHookableComponent(): void
    {
        $hookable = HookableComponentMotherObject::withTargetAndProps('some-component', ['some' => 'data']);
        $metadata = HookableMetadataMotherObject::withContextAndConfiguration(
            context: new ParameterBag(['some' => 'data']),
            configuration: new ParameterBag(['some' => 'configuration']),
        );

        $this->propsProvider->expects($this->once())->method('provide')->with($hookable, $metadata)->willReturn(['some' => 'props']);
        $this->componentRenderer->expects($this->once())->method('createAndRender')->with(
            'some-component',
            [
                'hookableMetadata' => $metadata,
                'some' => 'props',
            ]
        )->willReturn('some-rendered-component');

        $renderedComponent = $this->getTestSubject()->render($hookable, $metadata);

        $this->assertSame('some-rendered-component', $renderedComponent);
    }

    private function getTestSubject(): HookableComponentRenderer
    {
        return new HookableComponentRenderer($this->propsProvider, $this->componentRenderer);
    }
}
