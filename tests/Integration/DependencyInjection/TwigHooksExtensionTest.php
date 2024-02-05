<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Integration\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sylius\TwigHooks\DependencyInjection\TwigHooksExtension;

final class TwigHooksExtensionTest extends AbstractExtensionTestCase
{
    public function testItRegistersHookablesAsServices(): void
    {
        $this->load([
            'supported_hookable_types' => [
                'template' => HookableTemplate::class,
                'component' => HookableComponent::class,
            ],
            'hooks' => [
                'some_hook' => [
                    'some_hookable' => [
                        'type' => 'template',
                        'target' => '@SomeBundle/some_template.html.twig',
                        'data' => ['some' => 'data'],
                        'configuration' => [],
                        'priority' => 16,
                        'enabled' => false,
                    ],
                    'another_hookable' => [
                        'type' => 'component',
                        'target' => 'MyComponent',
                        'data' => ['some' => 'data'],
                        'configuration' => [],
                        'priority' => 16,
                        'enabled' => false,
                    ],
                ],
                'app.more_complex.hook_name' => [
                    'yet_another_hookable' => [
                        'type' => 'template',
                        'target' => '@SomeBundle/another_template.html.twig',
                        'data' => ['some' => 'data'],
                        'enabled' => false,
                    ],
                ]
            ],
        ]);

        $this->assertContainerBuilderHasService('twig_hooks.hook.some_hook.hookable.some_hookable', HookableTemplate::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'twig_hooks.hook.some_hook.hookable.some_hookable',
            'twig_hooks.hookable',
            ['priority' => 16],
        );

        $this->assertContainerBuilderHasService('twig_hooks.hook.some_hook.hookable.another_hookable', HookableComponent::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'twig_hooks.hook.some_hook.hookable.another_hookable',
            'twig_hooks.hookable',
            ['priority' => 16],
        );

        $this->assertContainerBuilderHasService('twig_hooks.hook.app.more_complex.hook_name.hookable.yet_another_hookable', HookableTemplate::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'twig_hooks.hook.app.more_complex.hook_name.hookable.yet_another_hookable',
            'twig_hooks.hookable',
            ['priority' => 0],
        );
    }

    public function testItThrowsAnExceptionWhenNotSupportedHookableTypeIsUsed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Hookable type "template" is not supported.');

        $this->load([
            'supported_hookable_types' => [],
            'hooks' => [
                'some_hook' => [
                    'some_hookable' => [
                        'type' => 'template',
                        'target' => '@SomeBundle/some_template.html.twig',
                    ],
                ],
            ],
        ]);
    }

    protected function getContainerExtensions(): array
    {
        return [
            new TwigHooksExtension(),
        ];
    }
}
