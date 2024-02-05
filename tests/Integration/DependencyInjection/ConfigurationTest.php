<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Integration\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\DependencyInjection\Configuration;

final class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testItReturnsDefaultConfiguration(): void
    {
        $this->assertProcessedConfigurationEquals(
            [],
            [
                'hooks' => [],
            ],
            'hooks.*'
        );
    }

    public function testItSetsDefaultValuesForHookable(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'hooks' => [
                        'some_hook' => [
                            'some_hookable' => [
                                'target' => 'some_target.html.twig',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'hooks' => [
                    'some_hook' => [
                        'some_hookable' => [
                            'type' => 'template',
                            'target' => 'some_target.html.twig',
                            'data' => [],
                            'configuration' => [],
                            'priority' => 0,
                            'enabled' => true,
                            'component' => null,
                            'template' => null,
                        ],
                    ],
                ],
            ],
            'hooks.*'
        );
    }

    public function testItAllowsToDefineSupportedHookableTypes(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'supported_hookable_types' => [
                        'template' => HookableTemplate::class,
                        'component' => HookableComponent::class,
                    ],
                ],
            ],
            [
                'supported_hookable_types' => [
                    'template' => HookableTemplate::class,
                    'component' => HookableComponent::class,
                ],
            ],
            'supported_hookable_types'
        );
    }

    public function testItAllowsToUseComponentShortcut(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'hooks' => [
                        'some_hook' => [
                            'some_hookable' => [
                                'component' => 'MyAwesomeComponent',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'hooks' => [
                    'some_hook' => [
                        'some_hookable' => [
                            'type' => 'component',
                            'target' => 'MyAwesomeComponent',
                            'data' => [],
                            'configuration' => [],
                            'priority' => 0,
                            'enabled' => true,
                            'component' => 'MyAwesomeComponent',
                            'template' => null,
                        ],
                    ],
                ],
            ],
            'hooks.*'
        );
    }

    public function testItAllowsToUseTemplateShortcut(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'hooks' => [
                        'some_hook' => [
                            'some_hookable' => [
                                'template' => 'some_target.html.twig',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'hooks' => [
                    'some_hook' => [
                        'some_hookable' => [
                            'type' => 'template',
                            'target' => 'some_target.html.twig',
                            'data' => [],
                            'configuration' => [],
                            'priority' => 0,
                            'enabled' => true,
                            'component' => null,
                            'template' => 'some_target.html.twig',
                        ],
                    ],
                ],
            ],
            'hooks.*'
        );
    }

    public function testItThrowsExceptionWhenBothTemplateAndComponentShortcutsAreDefined(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'hooks' => [
                        'some_hook' => [
                            'some_hookable' => [
                                'component' => 'MyAwesomeComponent',
                                'template' => 'some_target.html.twig',
                            ],
                        ],
                    ],
                ],
            ],
            'You cannot define both "component" and "template" at the same time.'
        );
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
