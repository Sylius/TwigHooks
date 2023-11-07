<?php

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
