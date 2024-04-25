<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\LiveComponent\Hydration;

use Sylius\TwigHooks\Bag\DataBagInterface;
use Symfony\UX\LiveComponent\Hydration\HydrationExtensionInterface;

final class DataBagHydrationExtension implements HydrationExtensionInterface
{
    public function supports(string $className): bool
    {
        return is_a($className, DataBagInterface::class, true);
    }

    public function hydrate(mixed $value, string $className): ?object
    {
        return new $className($value);
    }

    public function dehydrate(object $object): mixed
    {
        return $object->all();
    }
}
