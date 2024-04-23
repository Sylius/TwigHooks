<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hookable\Renderer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\HookableTemplate;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Hookable\Renderer\HookableTemplateRenderer;
use Tests\Sylius\TwigHooks\Utils\MotherObject\BaseHookableMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;
use Twig\Environment as Twig;

final class HookableTemplateRendererTest extends TestCase
{
    /** @var Twig&MockObject */
    private Twig $twig;

    protected function setUp(): void
    {
        $this->twig = $this->createMock(Twig::class);
    }

    public function testItSupportsOnlyHookableTemplates(): void
    {
        $hookableTemplate = BaseHookableMotherObject::withType(AbstractHookable::TYPE_TEMPLATE);
        $hookableComponent = BaseHookableMotherObject::withType(AbstractHookable::TYPE_COMPONENT);

        $this->assertTrue($this->getTestSubject()->supports($hookableTemplate));
        $this->assertFalse($this->getTestSubject()->supports($hookableComponent));
    }

    public function testItThrowsAnExceptionWhenTryingToRenderUnsupportedHookable(): void
    {
        $hookableComponent = BaseHookableMotherObject::withType(AbstractHookable::TYPE_COMPONENT);
        $metadata = $this->createMock(HookableMetadata::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Hookable must be the "template" type, but "component" given.');

        $this->getTestSubject()->render($hookableComponent, $metadata);
    }

    public function testItRendersHookableTemplate(): void
    {
        $metadata = $this->createMock(HookableMetadata::class);

        $this->twig->expects($this->once())->method('render')->with('some-template', [
            'hookable_metadata' => $metadata,
        ])->willReturn('some-rendered-template');

        $hookable = BaseHookableMotherObject::withTarget('some-template');
        $renderedTemplate = $this->getTestSubject()->render($hookable, $metadata);

        $this->assertSame('some-rendered-template', $renderedTemplate);
    }

    private function getTestSubject(): HookableTemplateRenderer
    {
        return new HookableTemplateRenderer($this->twig);
    }
}
