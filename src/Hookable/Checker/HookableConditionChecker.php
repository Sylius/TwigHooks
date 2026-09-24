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

namespace Sylius\TwigHooks\Hookable\Checker;

use Sylius\TwigHooks\Bag\DataBag;
use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Provider\Exception\InvalidExpressionException;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class HookableConditionChecker implements HookableConditionCheckerInterface
{
    public function __construct(
        private readonly ExpressionLanguage $expressionLanguage,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function isEnabled(AbstractHookable $hookable, array $context): bool
    {
        $condition = $hookable->condition;

        if (null === $condition) {
            return true;
        }

        $values = array_merge($context, ['_context' => new DataBag($context)]);

        try {
            return (bool) $this->expressionLanguage->evaluate(substr($condition, 2), $values);
        } catch (\Throwable $e) {
            throw new InvalidExpressionException(
                sprintf(
                    'Failed to evaluate the "%s" condition while rendering the "%s" hookable in the "%s" hook. Error: %s".',
                    $condition,
                    $hookable->name,
                    $hookable->hookName,
                    $e->getMessage(),
                ),
                previous: $e,
            );
        }
    }
}
