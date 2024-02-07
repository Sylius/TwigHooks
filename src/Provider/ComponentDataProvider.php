<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Provider;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ComponentDataProvider implements DataProviderInterface
{
    public function __construct (
        private ExpressionLanguage $expressionLanguage,
    ) {
    }

    public function provide(AbstractHookable $hookable, array $hookData): array
    {
        return $this->mapArrayRecursively(function (mixed $value) use ($hookData): mixed {
            if (is_string($value) && str_starts_with($value, '@=')) {
                return $this->expressionLanguage->evaluate(substr($value, 2), $hookData);
            }

            return $value;
        }, $hookable->getData());
    }

    /**
     * @param array<array-key, mixed> $array
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
