<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Functional\Twig;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment as Twig;

/**
 * @group kernel-required
 */
final class ParsingHookTagTest extends KernelTestCase
{
    public function testItRendersSingleHookName(): void
    {
        $this->assertSame(
            <<<EXPECTED
            <!-- BEGIN HOOK | name: "some_hook" -->
            
            <!--  END HOOK  | name: "some_hook" -->
            EXPECTED,
            $this->render('parsing_hook_tag_test/single_hook_name.html.twig'),
        );
    }

    public function testItRendersMultipleHookNames(): void
    {
        $this->assertSame(
            <<<EXPECTED
            <!-- BEGIN HOOK | name: "some_hook, another_hook" -->
            
            <!--  END HOOK  | name: "some_hook, another_hook" -->
            EXPECTED,
            $this->render('parsing_hook_tag_test/multiple_hook_names.html.twig'),
        );
    }

    private function render(string $path): string
    {
        /** @var Twig $twig */
        $twig = $this->getContainer()->get('twig');

        return $twig->render($path);
    }
}
