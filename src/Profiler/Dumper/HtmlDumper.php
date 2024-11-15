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

namespace Sylius\TwigHooks\Profiler\Dumper;

use Sylius\TwigHooks\Hookable\AbstractHookable;
use Sylius\TwigHooks\Hookable\HookableComponent;
use Sylius\TwigHooks\Hookable\HookableTemplate;
use Sylius\TwigHooks\Profiler\HookableProfile;
use Sylius\TwigHooks\Profiler\HookProfile;
use Sylius\TwigHooks\Profiler\Profile;

/** @internal */
final class HtmlDumper
{
    public function dump(Profile $profile): string
    {
        return sprintf('<pre>%s%s</pre>', \PHP_EOL, $this->dumpProfile($profile));
    }

    private function dumpProfile(Profile $profile): string
    {
        $rootProfiles = $profile->getRootProfiles();
        $str = '';

        foreach ($rootProfiles as $rootProfile) {
            $str .= $this->dumpHookProfile($rootProfile);
        }

        return $str;
    }

    private function dumpHookProfile(HookProfile $hookProfile, string $prefix = '', bool $sibling = false): string
    {
        $str = sprintf(
            '%s└ <span><span class="status-info">(Hook)</span> %s</span>',
            $prefix,
            $hookProfile->getName(),
        );
        $str .= \PHP_EOL;
        $prefix .= $sibling ? '│   ' : '    ';

        $numberOfHookables = \count($hookProfile->getHookablesProfiles());
        foreach ($hookProfile->getHookablesProfiles() as $index => $hookableProfile) {
            $str .= $this->dumpHookableProfile($hookableProfile, $prefix, $index + 1 !== $numberOfHookables);
        }

        return $str;
    }

    private function dumpHookableProfile(HookableProfile $hookableProfile, string $prefix = '', bool $sibling = false): string
    {
        $targetName = match (get_class($hookableProfile->getHookable())) {
            HookableTemplate::class => 'Template',
            HookableComponent::class => 'Component',
            default => throw new \InvalidArgumentException(sprintf('Unsupported hookable type %s', get_class($hookableProfile->getHookable()))),
        };

        $str = sprintf(
            '%s└ <span><span class="%s">(%s)</span> [↑ %d, ⏲ %d ms] %s (%s)</span>',
            $prefix,
            $targetName === 'Template' ? 'status-success' : 'status-warning',
            $targetName,
            $hookableProfile->getHookable()->priority(),
            $hookableProfile->getDuration(),
            $hookableProfile->getName(),
            $this->getTargetValue($hookableProfile->getHookable()),
        );
        $str .= \PHP_EOL;
        $prefix .= $sibling ? '│   ' : '    ';

        $numberOfChildren = \count($hookableProfile->getChildren());
        foreach ($hookableProfile->getChildren() as $index => $child) {
            $str .= $this->dumpHookProfile($child, $prefix, $index + 1 !== $numberOfChildren);
        }

        return $str;
    }

    private function getTargetValue(AbstractHookable $hookable): string
    {
        return match (get_class($hookable)) {
            HookableTemplate::class => $hookable->template,
            HookableComponent::class => $hookable->component,
            default => throw new \InvalidArgumentException(sprintf('Unsupported hookable type %s', get_class($hookable))),
        };
    }
}
