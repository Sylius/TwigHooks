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

namespace Sylius\TwigHooks\Twig\Component;

use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

trait HookableComponentTrait
{
    #[ExposeInTemplate('hookable_metadata')]
    public ?HookableMetadata $hookableMetadata = null;
}
