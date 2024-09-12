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

namespace Sylius\TwigHooks\Hookable\Merger;

use Sylius\TwigHooks\Hookable\AbstractHookable;

final class HookableMerger implements HookableMergerInterface
{
    /**
     * @throws \ReflectionException
     */
    public function merge(AbstractHookable ...$hookables): AbstractHookable
    {
        if ([] === $hookables) {
            throw new \InvalidArgumentException('At least one hookable must be passed to merge.');
        }

        /** @var class-string<AbstractHookable> $class */
        $class = get_class(end($hookables));

        $serializedHookables = array_map(
            static fn (AbstractHookable $hookable): array => $hookable->toArray(),
            $hookables,
        );

        $inputs = array_merge(...$serializedHookables);
        $arguments = $this->createConstructorArguments($class, $inputs);

        return new $class(...$arguments);
    }

    /**
     * @param class-string $class
     * @param array<string, mixed> $inputs
     *
     * @return array<string>
     *
     * @throws \ReflectionException
     */
    private function createConstructorArguments(string $class, array $inputs): array
    {
        $reflection = new \ReflectionClass($class);
        /** @var \ReflectionMethod $constructor */
        $constructor = $reflection->getConstructor();
        $parameters = array_map(
            static fn (\ReflectionParameter $parameter): string => $parameter->getName(),
            $constructor->getParameters(),
        );

        $arguments = [];

        foreach ($inputs as $inputName => $input) {
            if (!in_array($inputName, $parameters, true)) {
                continue;
            }

            $arguments[$inputName] = $input;
        }

        return $arguments;
    }
}
