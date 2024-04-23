<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Profiler\Dumper;

use Sylius\TwigHooks\Profiler\HookableProfile;
use Sylius\TwigHooks\Profiler\HookProfile;
use Sylius\TwigHooks\Profiler\Profile;

/** @internal */
final class HtmlDumper
{
    public function dump(Profile $profile): string
    {
        return sprintf('<pre>%s%s</pre>', PHP_EOL, $this->dumpProfile($profile));
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
        $str .= PHP_EOL;
        $prefix .= $sibling ? '│   ' : '    ';

        $numberOfHookables = \count($hookProfile->getHookablesProfiles());
        foreach ($hookProfile->getHookablesProfiles() as $index => $hookableProfile) {
            $str .= $this->dumpHookableProfile($hookableProfile, $prefix, $index + 1 !== $numberOfHookables);
        }

        return $str;
    }

    private function dumpHookableProfile(HookableProfile $hookableProfile, string $prefix = '', bool $sibling = false): string
    {
        $str = sprintf(
            '%s└ <span><span class="status-success">(%s)</span> [↑ %d, ⏲ %d ms] %s (%s)</span>',
            $prefix,
            ucfirst($hookableProfile->getHookable()->getType()),
            $hookableProfile->getHookable()->getPriority(),
            $hookableProfile->getDuration(),
            $hookableProfile->getName(),
            $hookableProfile->getHookable()->target,
        );
        $str .= PHP_EOL;
        $prefix .= $sibling ? '│   ' : '    ';

        $numberOfChildren = \count($hookableProfile->getChildren());
        foreach ($hookableProfile->getChildren() as $index => $child) {
            $str .= $this->dumpHookProfile($child, $prefix, $index + 1 !== $numberOfChildren);
        }

        return $str;
    }
}
