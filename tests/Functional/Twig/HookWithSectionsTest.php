<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Functional\Twig;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment as Twig;

/**
 * @group kernel-required
 */
final class HookWithSectionsTest extends KernelTestCase
{
    public function testItRendersHookWithSections(): void
    {
        $this->assertSame(
            <<<EXPECTED
            <!-- BEGIN HOOK | name: "hook_with_sections.index" -->
            <!-- BEGIN HOOKABLE | hook: "hook_with_sections.index", name: "block", template: "hook_with_sections/index/block.html.twig", priority: 0 -->
            some block
            <!--  END HOOKABLE  | hook: "hook_with_sections.index", name: "block", template: "hook_with_sections/index/block.html.twig", priority: 0 -->
            <!--  END HOOK  | name: "hook_with_sections.index" -->
            <!-- BEGIN HOOK | name: "hook_with_sections.index#section_a" -->

            <!--  END HOOK  | name: "hook_with_sections.index#section_a" -->
            <!-- BEGIN HOOK | name: "hook_with_sections.index#section_b" -->

            <!--  END HOOK  | name: "hook_with_sections.index#section_b" -->
            EXPECTED,
            $this->render('hook_with_sections/index.html.twig'),
        );
    }

    private function render(string $path): string
    {
        /** @var Twig $twig */
        $twig = $this->getContainer()->get('twig');

        return $twig->render($path);
    }
}
