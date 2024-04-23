<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Provider\ComponentPropsProvider;
use Sylius\TwigHooks\Provider\PropsProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableComponentMotherObject;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableMetadataMotherObject;

final class ComponentPropsProviderTest extends TestCase
{
    public function testItReturnsEmptyArrayWhenNoPropsDefined(): void
    {
        $hookable = HookableComponentMotherObject::withProps([]);
        $metadata = HookableMetadataMotherObject::some();

        $propsProvider = $this->createTestSubject();

        $this->assertSame([], $propsProvider->provide($hookable, $metadata));
    }

    public function testItReturnsProps(): void
    {
        $hookable = HookableComponentMotherObject::withProps(['message' => 'Hello, World!']);
        $metadata = HookableMetadataMotherObject::some();

        $propsProvider = $this->createTestSubject();

        $this->assertSame(['message' => 'Hello, World!'], $propsProvider->provide($hookable, $metadata));
    }

    public function testItEvaluatesExpressions(): void
    {
        $hookable = HookableComponentMotherObject::withProps([
            'name' => '@=_configuration.anonymize ? "Anon" : _context.username'
        ]);
        $anonymizeMetadata = HookableMetadataMotherObject::withContextAndConfiguration(
            context: [
                'username' => 'Jacob',
            ],
            configuration: [
                'anonymize' => true,
            ],
        );

        $propsProvider = $this->createTestSubject();

        $this->assertSame(['name' => 'Anon'], $propsProvider->provide($hookable, $anonymizeMetadata));

        $notAnonymizeMetadata = HookableMetadataMotherObject::withContextAndConfiguration(
            context: [
                'username' => 'Jacob',
            ],
            configuration: [
                'anonymize' => false,
            ],
        );

        $this->assertSame(['name' => 'Jacob'], $propsProvider->provide($hookable, $notAnonymizeMetadata));
    }

    private function createTestSubject(): PropsProviderInterface
    {
        return new ComponentPropsProvider(new ExpressionLanguage());
    }
}
