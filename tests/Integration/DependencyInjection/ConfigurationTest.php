<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Integration\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\DependencyInjection\Configuration;
use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Hookable\HookableTemplate;

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

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
