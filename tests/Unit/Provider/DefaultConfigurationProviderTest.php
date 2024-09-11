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

namespace Tests\Sylius\TwigHooks\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Provider\DefaultConfigurationProvider;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

final class DefaultConfigurationProviderTest extends TestCase
{
    public function testItProvidesConfiguration(): void
    {
        $hookable = HookableTemplateMotherObject::withConfiguration(['some' => 'configuration']);

        $defaultConfigurationProvider = $this->getTestSubject();
        $configuration = $defaultConfigurationProvider->provide($hookable);

        $this->assertSame(['some' => 'configuration'], $configuration);
    }

    private function getTestSubject(): DefaultConfigurationProvider
    {
        return new DefaultConfigurationProvider();
    }
}
