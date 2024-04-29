<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Hookable\Metadata;

use Sylius\TwigHooks\Bag\DataBagInterface;
use Sylius\TwigHooks\Bag\ScalarDataBagInterface;
use Sylius\TwigHooks\Hook\Metadata\HookMetadata;

interface HookableMetadataFactoryInterface
{
    public function create(HookMetadata $hookMetadata, DataBagInterface $context, ScalarDataBagInterface $configuration, array $prefixes = [],): HookableMetadata;
}
