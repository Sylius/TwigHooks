<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hookable\Renderer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Hookable\Renderer\HookableComponentRenderer;
use Sylius\TwigHooks\Provider\ConfigurationProviderInterface;
use Sylius\TwigHooks\Provider\DataProviderInterface;
use Symfony\UX\TwigComponent\ComponentRendererInterface;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableComponentMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

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
        $hookableTemplate = HookableTemplateMotherObject::some();
        $hookableComponent = HookableComponentMotherObject::some();

        $this->assertTrue($this->getTestSubject()->supports($hookableComponent));
        $this->assertFalse($this->getTestSubject()->supports($hookableTemplate));
    }

    public function testItThrowsAnExceptionWhenTryingToRenderUnsupportedHookable(): void
    {
        $hookableTemplate = HookableTemplateMotherObject::some();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('Hookable must be an instance of "%s".', HookableComponent::class)
        );

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

        $hookable = HookableComponentMotherObject::withTarget('some-component');
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
