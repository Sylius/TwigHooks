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

namespace Sylius\TwigHooks\Provider;

use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Provider\Exception\InvalidExpressionException;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ComponentPropsProvider implements PropsProviderInterface
{
    public function __construct(
        private ExpressionLanguage $expressionLanguage,
    ) {
    }

    public function provide(HookableComponent $hookable, HookableMetadata $metadata): array
    {
        $values = [
            '_context' => $metadata->context,
            '_configuration' => $metadata->configuration,
        ];

        return $this->mapArrayRecursively(function (mixed $value) use ($values, $hookable): mixed {
            if (is_string($value) && str_starts_with($value, '@=')) {
                try {
                    return $this->expressionLanguage->evaluate(substr($value, 2), $values);
                } catch (\Throwable $e) {
                    throw new InvalidExpressionException(
                        sprintf(
                            'Failed to evaluate the "%s" expression while rendering the "%s" hookable in the "%s" hook. Error: %s".',
                            $value,
                            $hookable->name,
                            $hookable->hookName,
                            $e->getMessage(),
                        ),
                        previous: $e,
                    );
                }
            }

            return $value;
        }, $hookable->props);
    }

    /**
     * @param array<array-key, mixed> $array
     *
     * @return array<array-key, mixed>
     */
    private function mapArrayRecursively(callable $callback, array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = is_array($value)
                ? $this->mapArrayRecursively($callback, $value)
                : $callback($value);
        }

        return $result;
    }
}
