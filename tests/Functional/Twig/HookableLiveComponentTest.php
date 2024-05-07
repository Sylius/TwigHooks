<?php

declare(strict_types=1);

namespace Tests\Sylius\TwigHooks\Functional\Twig;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment as Twig;

/**
 * @group kernel-required
 */
final class HookableLiveComponentTest extends KernelTestCase
{
    public function testItRendersHookableLiveComponentWithUsingTwigHooks(): void
    {
        $this->assertSame(
            <<<EXPECTED
            <!-- BEGIN HOOK | name: "hookable_live_component" -->
            <!-- BEGIN HOOKABLE | hook: "hookable_live_component", name: "dummy", component: "app:dummy", priority: 0 -->
            I'm a hookable!
            <!--  END HOOKABLE  | hook: "hookable_live_component", name: "dummy", component: "app:dummy", priority: 0 -->
            <!--  END HOOK  | name: "hookable_live_component" -->
            EXPECTED,
            $this->render('hookable_live_component/hookable_component_rendered_with_using_twig_hooks.html.twig'),
        );
    }

    public function testItRendersHookableLiveComponentWithoutUsingTwigHooks(): void
    {
        $this->assertStringContainsString(
            "I'm not a hookable :(",
            $this->render('hookable_live_component/hookable_component_rendered_without_using_twig_hooks.html.twig'),
        );
    }

    private function render(string $path): string
    {
        /** @var Twig $twig */
        $twig = $this->getContainer()->get('twig');

        return $twig->render($path);
    }
}
