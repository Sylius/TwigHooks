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

namespace Tests\Sylius\TwigHooks;

use Symfony\Component\DomCrawler\Crawler;

final class RenderedTwigTemplate implements \Stringable
{
    public function __construct(private string $html)
    {
    }

    public function crawler(): Crawler
    {
        if (!class_exists(Crawler::class)) {
            throw new \LogicException(sprintf('"symfony/dom-crawler" is required to use "%s()" (install with "composer require symfony/dom-crawler").', __METHOD__));
        }

        return new Crawler($this->html);
    }

    public function toString(): string
    {
        return $this->html;
    }

    public function __toString(): string
    {
        return $this->html;
    }
}
