<?php

declare(strict_types=1);

namespace Functional\Twig;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment as Twig;

/**
 * @group kernel-required
 */
final class RestrictingContextScopeTest extends KernelTestCase
{
    public function testItRendersSingleHookName(): void
    {
        $this->assertSame(
            <<<EXPECTED
            <!-- BEGIN HOOK | name: "restricting_context_scope.index" -->
            <!-- BEGIN HOOKABLE | hook: "restricting_context_scope.index", name: "with_only", template: "restricting_context_scope/index/with_only.html.twig", priority: 0 -->
            <!-- BEGIN HOOK | name: "restricting_context_scope.index.with_only" -->
            <!-- BEGIN HOOKABLE | hook: "restricting_context_scope.index.with_only", name: "some", template: "restricting_context_scope/index/block/some.html.twig", priority: 0 -->
            Only
            
            is "some" defined: No
            is "other" defined: No
            <!--  END HOOKABLE  | hook: "restricting_context_scope.index.with_only", name: "some", template: "restricting_context_scope/index/block/some.html.twig", priority: 0 -->
            <!--  END HOOK  | name: "restricting_context_scope.index.with_only" -->
            <!--  END HOOKABLE  | hook: "restricting_context_scope.index", name: "with_only", template: "restricting_context_scope/index/with_only.html.twig", priority: 0 -->
            <!-- BEGIN HOOKABLE | hook: "restricting_context_scope.index", name: "without_only", template: "restricting_context_scope/index/without_only.html.twig", priority: 0 -->
            <!-- BEGIN HOOK | name: "restricting_context_scope.index.without_only" -->
            <!-- BEGIN HOOKABLE | hook: "restricting_context_scope.index.without_only", name: "some", template: "restricting_context_scope/index/block/some.html.twig", priority: 0 -->
            Without only
            
            is "some" defined: Yes
            is "other" defined: Yes
            <!--  END HOOKABLE  | hook: "restricting_context_scope.index.without_only", name: "some", template: "restricting_context_scope/index/block/some.html.twig", priority: 0 -->
            <!--  END HOOK  | name: "restricting_context_scope.index.without_only" -->
            <!--  END HOOKABLE  | hook: "restricting_context_scope.index", name: "without_only", template: "restricting_context_scope/index/without_only.html.twig", priority: 0 -->
            <!--  END HOOK  | name: "restricting_context_scope.index" -->
            EXPECTED,
            $this->render('restricting_context_scope/index.html.twig'),
        );
    }

    private function render(string $path): string
    {
        /** @var Twig $twig */
        $twig = $this->getContainer()->get('twig');

        return $twig->render($path);
    }
}
