<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Bag;

class ScalarDataBag extends DataBag implements ScalarDataBagInterface
{
    /**
     * @param array<string, scalar> $container
     */
    public function __construct(array $container = [])
    {
        $this->validateValues($container);

        parent::__construct($container);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!is_scalar($value)) {
            throw new \InvalidArgumentException('The value must be a scalar.');
        }

        parent::offsetSet($offset, $value);
    }

    /**
     * @param array<string, mixed> $values
     */
    private function validateValues(array $values): void
    {
        foreach ($values as $value) {
            if (is_array($value)) {
                $this->validateValues($value);
                continue;
            }

            if (!is_scalar($value)) {
                throw new \InvalidArgumentException('The value must be a scalar.');
            }
        }
    }
}
