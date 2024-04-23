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

    public function testItRendersLocallyPrefixedSingleHookName(): void
    {
        $this->assertSame(
            <<<EXPECTED
            <!-- BEGIN HOOK | name: "app.body, twig_hook.body" -->
            
            <!--  END HOOK  | name: "app.body, twig_hook.body" -->
            EXPECTED,
            $this->render('parsing_hook_tag_test/locally_prefixed_single_hook_name.html.twig'),
        );
    }

    public function testItRendersLocallyPrefixedMultipleHookNames(): void
    {
        $this->assertSame(
            <<<EXPECTED
            <!-- BEGIN HOOK | name: "app.body, twig_hook.body, app.another_body, twig_hook.another_body" -->
            
            <!--  END HOOK  | name: "app.body, twig_hook.body, app.another_body, twig_hook.another_body" -->
            EXPECTED,
            $this->render('parsing_hook_tag_test/locally_prefixed_multiple_hook_names.html.twig'),
        );
    }

    public function testItRendersHookWithHookablesAndAutoprefixingEnabled(): void
    {
        $this->assertSame(
            <<<EXPECTED
            <!-- BEGIN HOOK | name: "hook_with_hookable" -->
            <!-- BEGIN HOOKABLE | hook: "hook_with_hookable", type: "template", name: "hookable", target: "parsing_hook_tag_test/hook_with_hookables/hookable_with_hook.html.twig", priority: 0 -->
            <!-- BEGIN HOOK | name: "hook_with_hookable.hookable_with_hook" -->
            
            <!--  END HOOK  | name: "hook_with_hookable.hookable_with_hook" -->
            <!--  END HOOKABLE  | hook: "hook_with_hookable", type: "template", name: "hookable", target: "parsing_hook_tag_test/hook_with_hookables/hookable_with_hook.html.twig", priority: 0 -->
            <!--  END HOOK  | name: "hook_with_hookable" -->
            EXPECTED,
            $this->render('parsing_hook_tag_test/hook_with_hookables.html.twig'),
        );
    }

    private function render(string $path): string
    {
        /** @var Twig $twig */
        $twig = $this->getContainer()->get('twig');

        return $twig->render($path);
    }
}
