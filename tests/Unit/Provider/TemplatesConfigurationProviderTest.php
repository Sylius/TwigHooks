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
use Sylius\TwigHooks\Provider\TemplateConfigurationProvider;
use Sylius\TwigHooks\Provider\TemplateConfigurationProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableMetadataMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

final class TemplatesConfigurationProviderTest extends TestCase
{
    public function testItReturnsEmptyArrayWhenNoConfigurationDefined(): void
    {
        $hookable = HookableTemplateMotherObject::withConfiguration([]);
        $metadata = HookableMetadataMotherObject::some();

        $templateConfigurationProvider = $this->createTestSubject();

        $this->assertSame([], $templateConfigurationProvider->provide($hookable, $metadata));
    }

    public function testItReturnsConfiguration(): void
    {
        $hookable = HookableTemplateMotherObject::withConfiguration(['message' => 'Hello, World!']);
        $metadata = HookableMetadataMotherObject::some();

        $templateConfigurationProvider = $this->createTestSubject();

        $this->assertSame(['message' => 'Hello, World!'], $templateConfigurationProvider->provide($hookable, $metadata));
    }

    public function testItEvaluatesExpressions(): void
    {
        $hookable = HookableTemplateMotherObject::withConfiguration([
            'username' => '@=_context.username',
        ]);
        $metadata = HookableMetadataMotherObject::withContext(
            ['username' => 'Jacob'],
        );

        $templateConfigurationProvider = $this->createTestSubject();

        $this->assertSame(['username' => 'Jacob'], $templateConfigurationProvider->provide($hookable, $metadata));
    }

    private function createTestSubject(): TemplateConfigurationProviderInterface
    {
        return new TemplateConfigurationProvider(new ExpressionLanguage());
    }
}
