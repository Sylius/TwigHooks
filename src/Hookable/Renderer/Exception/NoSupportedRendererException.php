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

namespace Sylius\TwigHooks\Hookable\Renderer\Exception;

class NoSupportedRendererException extends \RuntimeException
{
    public function __construct(
        string $hookName,
        string $hookableName,
        string $message = 'No supported renderer found for the hook "%s" and "%s" hookable.',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                $message,
                $hookName,
                $hookableName,
            ),
            $code,
            $previous,
        );
    }
}
