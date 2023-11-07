<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Provider\DefaultDataProvider;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

final class DefaultDataProviderTest extends TestCase
{
    public function testItProvidesData(): void
    {
        $hookable = HookableTemplateMotherObject::withData(['some' => 'data', 'another' => 'data']);
        $hookData = ['some' => 'datum', 'yet_another' => 'data'];

        $defaultDataProvider = $this->getTestSubject();
        $data = $defaultDataProvider->provide($hookable, $hookData);

        $this->assertSame(['some' => 'datum', 'another' => 'data', 'yet_another' => 'data'], $data);
    }

    private function getTestSubject(): DefaultDataProvider
    {
        return new DefaultDataProvider();
    }
}
