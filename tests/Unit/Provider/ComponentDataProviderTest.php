<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Provider\ComponentDataProvider;
use Sylius\TwigHooks\Provider\DataProviderInterface;
use Sylius\TwigHooks\Provider\Exception\InvalidExpressionException;
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
        $hookable = BaseHookableMotherObject::withData(['some_key' => '@=some']);

        $dataProvider = $this->createTestSubject();

        $this->assertSame(['some_key' => 'data'], $dataProvider->provide($hookable, ['some' => 'data', 'another' => 'data']));
    }

    public function testItThrowsCustomExceptionWhenEvaluatingAnExpressionFails(): void
    {
        $hookable = BaseHookableMotherObject::withData(['some_key' => '@=some.nonExistingMethod()']);

        $dataProvider = $this->createTestSubject();

        $this->expectException(InvalidExpressionException::class);
        $this->expectExceptionMessage(
            'Failed to evaluate the "@=some.nonExistingMethod()" expression while rendering the "some_name" hookable in the "some_hook" hook. Error: Unable to call method "nonExistingMethod" of non-object "some".".',
        );

        $dataProvider->provide($hookable, ['some' => 'data', 'another' => 'data']);
    }

    private function createTestSubject(): DataProviderInterface
    {
        return new ComponentDataProvider(
            new ExpressionLanguage(),
        );
    }
}
