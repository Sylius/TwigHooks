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

namespace Tests\Sylius\TwigHooks\Integration\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sylius\TwigHooks\DependencyInjection\SyliusTwigHooksExtension;
use Sylius\TwigHooks\Hookable\DisabledHookable;
use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Hookable\HookableTemplate;

final class SyliusTwigHooksExtensionTest extends AbstractExtensionTestCase
{
    public function testItSetsEnableAutoprefixingParameter(): void
    {
        $this->load([
            'enable_autoprefixing' => true,
            'hooks' => [],
            'supported_hookable_types' => [],
        ]);

        $this->assertContainerBuilderHasParameter('twig_hooks.enable_autoprefixing', true);
    }

    public function testItRegistersHookablesAsServices(): void
    {
        $this->load([
            'supported_hookable_types' => [
                'template' => HookableTemplate::class,
                'component' => HookableComponent::class,
                'disabled' => DisabledHookable::class,
            ],
            'hooks' => [
                'some_hook' => [
                    'some_hookable' => [
                        'template' => '@SomeBundle/some_template.html.twig',
                        'context' => ['some' => 'context'],
                        'configuration' => [],
                        'priority' => 16,
                    ],
                    'another_hookable' => [
                        'component' => 'MyComponent',
                        'context' => ['some' => 'context'],
                        'configuration' => [],
                        'priority' => 16,
                    ],
                ],
                'app.more_complex.hook_name' => [
                    'yet_another_hookable' => [
                        'template' => '@SomeBundle/another_template.html.twig',
                        'context' => ['some' => 'context'],
                        'enabled' => false,
                    ],
                ],
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

        $this->assertContainerBuilderHasService('twig_hooks.hook.app.more_complex.hook_name.hookable.yet_another_hookable', DisabledHookable::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'twig_hooks.hook.app.more_complex.hook_name.hookable.yet_another_hookable',
            'twig_hooks.hookable',
            ['priority' => 0],
        );
    }

    protected function getContainerExtensions(): array
    {
        return [
            new SyliusTwigHooksExtension(),
        ];
    }
}
