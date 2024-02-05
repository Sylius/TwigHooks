<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Provider\DefaultConfigurationProvider;
use Tests\Sylius\TwigHooks\Utils\MotherObject\BaseHookableMotherObject;

final class DefaultConfigurationProviderTest extends TestCase
{
    public function testItProvidesConfiguration(): void
    {
        $hookable = BaseHookableMotherObject::withConfiguration(['some' => 'configuration']);

        $defaultConfigurationProvider = $this->getTestSubject();
        $configuration = $defaultConfigurationProvider->provide($hookable);

        $this->assertSame(['some' => 'configuration'], $configuration);
    }

    private function getTestSubject(): DefaultConfigurationProvider
    {
        return new DefaultConfigurationProvider();
    }
}
