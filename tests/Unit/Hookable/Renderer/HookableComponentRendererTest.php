<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hookable\Renderer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\Renderer\HookableComponentRenderer;
use Sylius\TwigHooks\Provider\ConfigurationProviderInterface;
use Sylius\TwigHooks\Provider\DataProviderInterface;
use Symfony\UX\TwigComponent\ComponentRendererInterface;
use Tests\Sylius\TwigHooks\Utils\MotherObject\BaseHookableMotherObject;

final class HookableComponentRendererTest extends TestCase
{
    /** @var ComponentRendererInterface&MockObject */
    private ComponentRendererInterface $componentRenderer;

    /** @var DataProviderInterface&MockObject */
    private DataProviderInterface $dataProvider;

    /** @var ConfigurationProviderInterface&MockObject */
    private ConfigurationProviderInterface $configurationProvider;

    protected function setUp(): void
    {
        $this->componentRenderer = $this->createMock(ComponentRendererInterface::class);
        $this->dataProvider = $this->createMock(DataProviderInterface::class);
        $this->configurationProvider = $this->createMock(ConfigurationProviderInterface::class);
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

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Hookable must be the "component" type, but "template" given.');

        $this->getTestSubject()->render($hookableTemplate);
    }

    public function testItRendersHookableComponent(): void
    {
        $this->dataProvider->expects($this->once())->method('provide')->willReturn(['some' => 'data']);
        $this->configurationProvider->expects($this->once())->method('provide')->willReturn(['some' => 'configuration']);

        $this->componentRenderer->expects($this->once())->method('createAndRender')->with(
            'some-component',
            [
                HookableComponentRenderer::HOOKABLE_DATA_PARAMETER => ['some' => 'data'],
                HookableComponentRenderer::HOOKABLE_CONFIGURATION_PARAMETER => ['some' => 'configuration'],
            ]
        )->willReturn('some-rendered-component');

        $hookable = BaseHookableMotherObject::withTypeAndTarget(AbstractHookable::TYPE_COMPONENT, 'some-component');
        $renderedComponent = $this->getTestSubject()->render($hookable);

        $this->assertSame('some-rendered-component', $renderedComponent);
    }

    private function getTestSubject(): HookableComponentRenderer
    {
        return new HookableComponentRenderer(
            $this->componentRenderer,
            $this->dataProvider,
            $this->configurationProvider,
        );
    }
}
