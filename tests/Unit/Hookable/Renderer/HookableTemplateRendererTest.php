<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hookable\Renderer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\HookableTemplate;
use Sylius\TwigHooks\Hookable\Renderer\HookableTemplateRenderer;
use Sylius\TwigHooks\Provider\ConfigurationProviderInterface;
use Sylius\TwigHooks\Provider\DataProviderInterface;
use Sylius\TwigHooks\Twig\Runtime\HooksRuntime;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableComponentMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;
use Twig\Environment as Twig;

final class HookableTemplateRendererTest extends TestCase
{
    /** @var Twig&MockObject */
    private Twig $twig;

    /** @var DataProviderInterface&MockObject */
    private DataProviderInterface $dataProvider;

    /** @var ConfigurationProviderInterface&MockObject */
    private ConfigurationProviderInterface $configurationProvider;

    protected function setUp(): void
    {
        $this->twig = $this->createMock(Twig::class);
        $this->dataProvider = $this->createMock(DataProviderInterface::class);
        $this->configurationProvider = $this->createMock(ConfigurationProviderInterface::class);
    }

    public function testItSupportsOnlyHookableTemplates(): void
    {
        $hookableTemplate = HookableTemplateMotherObject::some();
        $hookableComponent = HookableComponentMotherObject::some();

        $this->assertTrue($this->getTestSubject()->supports($hookableTemplate));
        $this->assertFalse($this->getTestSubject()->supports($hookableComponent));
    }

    public function testItThrowsAnExceptionWhenTryingToRenderUnsupportedHookable(): void
    {
        $hookableComponent = HookableComponentMotherObject::some();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('Hookable must be an instance of "%s".', HookableTemplate::class)
        );

        $this->getTestSubject()->render($hookableComponent);
    }

    public function testItRendersHookableTemplate(): void
    {
        $this->dataProvider->expects($this->once())->method('provide')->willReturn(['some' => 'data']);
        $this->configurationProvider->expects($this->once())->method('provide')->willReturn(['some' => 'configuration']);

        $this->twig->expects($this->once())->method('render')->with('some-template', [
            HooksRuntime::HOOKABLE_DATA_PARAMETER => ['some' => 'data'],
            HooksRuntime::HOOKABLE_CONFIGURATION_PARAMETER => ['some' => 'configuration'],
        ])->willReturn('some-rendered-template');

        $hookable = HookableTemplateMotherObject::withTarget('some-template');
        $renderedTemplate = $this->getTestSubject()->render($hookable);

        $this->assertSame('some-rendered-template', $renderedTemplate);
    }

    private function getTestSubject(): HookableTemplateRenderer
    {
        return new HookableTemplateRenderer(
            $this->twig,
            $this->dataProvider,
            $this->configurationProvider
        );
    }
}
