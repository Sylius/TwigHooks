<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Functional\Twig;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment as Twig;

/**
 * @group kernel-required
 */
final class EnabledHookableConditionTest extends KernelTestCase
{
    public function testItRendersHookableWhenConditionIsSatisfied(): void
    {
        $result = $this->render('enabled_by_condition/index.html.twig', ['show' => true]);

        $this->assertStringContainsString('with_condition_true', $result);
        $this->assertStringContainsString('Condition block rendered.', $result);
        $this->assertStringNotContainsString('with_condition_false', $result);
    }

    public function testItSkipsHookableWhenConditionIsNotSatisfied(): void
    {
        $result = $this->render('enabled_by_condition/index.html.twig', ['show' => false]);

        $this->assertStringContainsString('with_condition_false', $result);
        $this->assertStringNotContainsString('with_condition_true', $result);
    }

    /**
     * @param array<string, mixed> $variables
     */
    private function render(string $path, array $variables = []): string
    {
        /** @var Twig $twig */
        $twig = $this->getContainer()->get('twig');

        return $twig->render($path, $variables);
    }
}
