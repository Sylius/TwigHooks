<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Profiler;

use Sylius\TwigHooks\Profiler\Dumper\HtmlDumper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;
use Twig\Markup;

/** @internal */
final class HooksDataCollector extends DataCollector implements LateDataCollectorInterface
{
    public function __construct (
        private Profile $profile,
    ) {
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
    }

    public function getName(): string
    {
        return 'sylius_twig_hooks';
    }

    public function lateCollect(): void
    {
        $this->data = ['profile' => serialize($this->profile)];
    }

    private function getProfile(): Profile
    {
        return $this->profile ??= unserialize($this->data['profile']);
    }

    public function getTotalDuration(): int|float
    {
        return $this->getProfile()->getTotalDuration();
    }

    public function getNumberOfHooks(): int
    {
        return $this->getProfile()->getNumberOfHooks();
    }

    public function getNumberOfHookables(): int
    {
        return $this->getProfile()->getNumberOfHookables();
    }

    public function getCallGraph(): Markup
    {
        $dump = (new HtmlDumper())->dump($this->getProfile());

        return new Markup($dump, 'UTF-8');
    }

    public function reset(): void
    {
        $this->profile->reset();
        $this->data = [];
    }
}
