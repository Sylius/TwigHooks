<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hookable\Renderer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Hookable\Renderer\HookableComponentRenderer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\UX\TwigComponent\ComponentRendererInterface;
use Tests\Sylius\TwigHooks\Utils\MotherObject\BaseHookableMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableMetadataMotherObject;

final class HookableComponentRendererTest extends TestCase
{
    /** @var ComponentRendererInterface&MockObject */
    private ComponentRendererInterface $componentRenderer;

    protected function setUp(): void
    {
        $this->componentRenderer = $this->createMock(ComponentRendererInterface::class);
    }

    public function testItSupportsOnlyHookableComponents(): void
    {
        $hookableTemplate = BaseHookableMotherObject::withType(AbstractHookable::TYPE_TEMPLATE);
        $hookableComponent = BaseHookableMotherObject::withType(AbstractHookable::TYPE_COMPONENT);

        $this->assertTrue($this->getTestSubject()->supports($hookableComponent));
        $this->assertFalse($this->getTestSubject()->supports($hookableTemplate));
    }

    public function testItThrowsAnExceptionWhenTryingToRenderUnsupportedHookable(): void
    {
        $hookableTemplate = BaseHookableMotherObject::withType(AbstractHookable::TYPE_TEMPLATE);
        $metadata = $this->createMock(HookableMetadata::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Hookable must be the "component" type, but "template" given.');

        $this->getTestSubject()->render($hookableTemplate, $metadata);
    }

    public function testItRendersHookableComponent(): void
    {
        $metadata = HookableMetadataMotherObject::withContextAndConfiguration(
            context: new ParameterBag(['some' => 'data']),
            configuration: new ParameterBag(['some' => 'configuration']),
        );

        $this->componentRenderer->expects($this->once())->method('createAndRender')->with(
            'some-component',
            [
                HookableComponentRenderer::HOOKABLE_CONFIGURATION_PARAMETER => ['some' => 'configuration'],
                'some' => 'data',
            ]
        )->willReturn('some-rendered-component');

        $hookable = BaseHookableMotherObject::withTypeAndTarget(AbstractHookable::TYPE_COMPONENT, 'some-component');
        $renderedComponent = $this->getTestSubject()->render($hookable, $metadata);

        $this->assertSame('some-rendered-component', $renderedComponent);
    }

    private function getTestSubject(): HookableComponentRenderer
    {
        return new HookableComponentRenderer($this->componentRenderer);
    }
}
