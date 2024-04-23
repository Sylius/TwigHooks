<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Provider\DefaultContextProvider;
use Tests\Sylius\TwigHooks\Utils\MotherObject\HookableTemplateMotherObject;

final class DefaultContextProviderTest extends TestCase
{
    public function testItProvidesData(): void
    {
        $hookable = HookableTemplateMotherObject::withContext(['some' => 'data', 'another' => 'data']);
        $hookContext = ['some' => 'datum', 'yet_another' => 'data'];

        $defaultContextProvider = $this->getTestSubject();
        $context = $defaultContextProvider->provide($hookable, $hookContext);

        $this->assertSame(['some' => 'data', 'yet_another' => 'data', 'another' => 'data'], $context);
    }

    private function getTestSubject(): DefaultContextProvider
    {
        return new DefaultContextProvider();
    }
}
