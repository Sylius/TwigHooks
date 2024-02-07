<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Provider\ComponentDataProvider;
use Sylius\TwigHooks\Provider\DataProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Tests\Sylius\TwigHooks\Utils\MotherObject\BaseHookableMotherObject;

final class ComponentDataProviderTest extends TestCase
{
    public function testItReturnsEmptyArrayWhenNoDataIsProvidedOnHookable(): void
    {
        $hookable = BaseHookableMotherObject::some();

        $dataProvider = $this->createTestSubject();

        $this->assertSame([], $dataProvider->provide($hookable, []));
        $this->assertSame([], $dataProvider->provide($hookable, ['some' => 'data']));
    }

    public function testItReturnsDataFromHookable(): void
    {
        $hookable = BaseHookableMotherObject::withData(['some' => 'data']);

        $dataProvider = $this->createTestSubject();

        $this->assertSame(['some' => 'data'], $dataProvider->provide($hookable, []));
        $this->assertSame(['some' => 'data'], $dataProvider->provide($hookable, ['more' => 'data']));
    }

    public function testItPassesTemplateLevelDataToExpressionLanguage(): void
    {
        $hookable = BaseHookableMotherObject::withData(['some_key' => '@=data["some"]']);

        $dataProvider = $this->createTestSubject();

        $this->assertSame(['some_key' => 'data'], $dataProvider->provide($hookable, ['some' => 'data']));
    }

    private function createTestSubject(): DataProviderInterface
    {
        return new ComponentDataProvider(
            new ExpressionLanguage(),
        );
    }
}
