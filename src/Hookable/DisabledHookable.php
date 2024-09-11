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

namespace Sylius\TwigHooks\Hookable;

class DisabledHookable extends AbstractHookable
{
    public function __construct(
        string $hookName,
        string $name,
        array $context = [],
        array $configuration = [],
        ?int $priority = null,
    ) {
        parent::__construct($hookName, $name, $context, $configuration, $priority);
    }

    public function toArray(): array
    {
        return [
            'hookName' => $this->hookName,
            'name' => $this->name,
            'context' => $this->context,
            'configuration' => $this->configuration,
            'priority' => $this->priority(),
        ];
    }
}
