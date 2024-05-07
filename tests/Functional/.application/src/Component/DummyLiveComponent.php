<?php

declare(strict_types=1);

namespace TestApplication\Sylius\TwigHooks\Component;

use Sylius\TwigHooks\LiveComponent\HookableLiveComponentTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('app:dummy_live', template: '_component/dummy_live.html.twig')]
final class DummyLiveComponent
{
    use DefaultActionTrait;
    use HookableLiveComponentTrait;
}
