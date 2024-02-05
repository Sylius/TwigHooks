<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Integration\Twig\Runtime;

use Sylius\TwigHooks\Twig\Runtime\HooksRuntime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group kernel-required
 */
final class HooksRuntimeTest extends KernelTestCase
{
    /**
     * @dataProvider getHookNameConversionData
     */
    public function testItConvertsStringsToHookNames(string $input, string $expectedOutput, array $suffix = []): void
    {
        $this->assertSame($expectedOutput, $this->getTestSubject()->createHookName($input, ...$suffix));
    }

    public function testItRendersHook(): void
    {
        $this->markTestSkipped('Not implemented yet.');
    }

    public function testItRendersHookable(): void
    {
        $this->markTestSkipped('Not implemented yet.');
    }

    /**
     * @return array
     */
    public function getHookNameConversionData(): array
    {
        return [
            'simple' => ['@SyliusShop/_product.html.twig', 'sylius_shop.product'],
            'with slash' => ['@SyliusShop/_product/variant.html.twig', 'sylius_shop.product.variant'],
            'with camel case' => ['@SyliusShop/_product/variant/optionValue.html.twig', 'sylius_shop.product.variant.option_value'],
            'without bundle prefix' => ['some_template.html.twig', 'some_template'],
            'with single suffix part' => ['@SyliusShop/_product.html.twig', 'sylius_shop.product.suffix', ['suffix']],
            'with multiple suffix parts' => ['@SyliusShop/_product.html.twig', 'sylius_shop.product.suffix.part', ['suffix', 'part']],
        ];
    }

    private function getTestSubject(): HooksRuntime
    {
        return $this->getContainer()->get(HooksRuntime::class);
    }
}
