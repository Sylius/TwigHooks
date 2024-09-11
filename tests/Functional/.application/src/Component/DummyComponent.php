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

namespace TestApplication\Sylius\TwigHooks\Component;

use Sylius\TwigHooks\Twig\Component\HookableComponentTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('app:dummy', template: '_component/dummy.html.twig')]
final class DummyComponent
{
    use DefaultActionTrait;
    use HookableComponentTrait;
}
