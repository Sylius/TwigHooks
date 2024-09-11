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

namespace Sylius\TwigHooks\Hookable\Metadata;

use Sylius\TwigHooks\Bag\DataBagInterface;
use Sylius\TwigHooks\Bag\ScalarDataBagInterface;
use Sylius\TwigHooks\Hook\Metadata\HookMetadata;

interface HookableMetadataFactoryInterface
{
    /**
     * @param array<string> $prefixes
     */
    public function create(HookMetadata $hookMetadata, DataBagInterface $context, ScalarDataBagInterface $configuration, array $prefixes = []): HookableMetadata;
}
