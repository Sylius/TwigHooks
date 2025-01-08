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

use Sylius\TwigHooks\Hookable\HookableTemplate;
use Sylius\TwigHooks\Hookable\Metadata\HookableMetadata;
use Sylius\TwigHooks\Provider\Exception\InvalidExpressionException;

interface TemplateConfigurationProviderInterface
{
    /**
     * @throws InvalidExpressionException
     *
     * @return array<string, mixed>
     */
    public function provide(HookableTemplate $hookable, HookableMetadata $metadata): array;
}
