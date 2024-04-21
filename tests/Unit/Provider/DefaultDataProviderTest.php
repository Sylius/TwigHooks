<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Provider\DefaultContextProvider;
use Tests\Sylius\TwigHooks\Utils\MotherObject\BaseHookableMotherObject;

final class DefaultDataProviderTest extends TestCase
{
    public function testItProvidesData(): void
    {
        $hookable = BaseHookableMotherObject::withData(['some' => 'data', 'another' => 'data']);
        $hookData = ['some' => 'datum', 'yet_another' => 'data'];

        $defaultDataProvider = $this->getTestSubject();
        $data = $defaultDataProvider->provide($hookable, $hookData);

        $this->assertSame(['some' => 'datum', 'another' => 'data', 'yet_another' => 'data'], $data);
    }

    private function getTestSubject(): DefaultContextProvider
    {
        return new DefaultContextProvider();
    }
}
