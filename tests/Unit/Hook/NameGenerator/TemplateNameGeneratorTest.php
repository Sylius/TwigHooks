<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Unit\Hook\NameGenerator;

use PHPUnit\Framework\TestCase;
use Sylius\TwigHooks\Hook\NameGenerator\TemplateNameGenerator;

final class TemplateNameGeneratorTest extends TestCase
{
    /**
     * @dataProvider itGeneratesNameDataProvider
     */
    public function testItGeneratesName(string $input, array $extraParts, string $expectedResult): void
    {
        $subject = $this->createTestSubject();

        self::assertSame($expectedResult, $subject->generate($input, ...$extraParts));
    }

    public function itGeneratesNameDataProvider(): array
    {
        return [
            ['@SyliusShop/layout.html.twig', ['header'], 'sylius_shop.layout.header'],
            ['@SyliusShop/layout.html.twig', ['header', 'menu'], 'sylius_shop.layout.header.menu'],
            ['@SyliusShop/layout.html.twig', ['header', 'menu', 'footer'], 'sylius_shop.layout.header.menu.footer'],
            ['@SyliusShop/_template.html.twig', [], 'sylius_shop.template'],
            ['@SyliusShop/_template.html.twig', ['header'], 'sylius_shop.template.header'],
            ['@SyliusShop/_template.html.twig', ['header', 'hamburger_menu'], 'sylius_shop.template.header.hamburger_menu'],
            ['@SyliusShop/_template.html.twig', ['header', 'hamburgerMenu'], 'sylius_shop.template.header.hamburger_menu'],
        ];
    }

    private function createTestSubject(): TemplateNameGenerator
    {
        return new TemplateNameGenerator();
    }
}
