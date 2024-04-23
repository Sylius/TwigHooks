<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Registry;

use Laminas\Stdlib\SplPriorityQueue;
use Sylius\TwigHooks\Hookable\AbstractHookable;

/** @internal */
class HookablesRegistry
{
    /** @var array<string, array<AbstractHookable>> */
    private array $hookables = [];

    /**
     * @param iterable<AbstractHookable> $hookables
     */
    public function __construct(iterable $hookables)
    {
        /** @var AbstractHookable $hookable */
        foreach ($hookables as $hookable) {
            if (!$hookable instanceof AbstractHookable) {
                throw new \InvalidArgumentException(
                    sprintf('All elements must be an instance of "%s".', AbstractHookable::class)
                );
            }

            $this->hookables[$hookable->hookName][$hookable->name] = $hookable;
        }
    }

    /**
     * @param string|array<string> $hooksNames
     *
     * @return array<AbstractHookable>
     */
    public function getEnabledFor(string|array $hooksNames): array
    {
        $hooksNames = is_string($hooksNames) ? [$hooksNames] : $hooksNames;
        $hookables = array_values(
            array_filter(
                $this->mergeHookables($hooksNames),
                static fn (AbstractHookable $hookable): bool => $hookable->isEnabled(),
            ),
        );

        $priorityQueue = new SplPriorityQueue();
        foreach ($hookables as $hookable) {
            $priorityQueue->insert($hookable, $hookable->getPriority());
        }

        return $priorityQueue->toArray();
    }

    /**
     * @param array<string> $hooksNames
     *
     * @return array<AbstractHookable>
     */
    private function mergeHookables(array $hooksNames): array
    {
        /** @var array<AbstractHookable> $mergedHookables */
        $mergedHookables = [];

        foreach (array_reverse($hooksNames) as $hookName) {
            $hookables = $this->hookables[$hookName] ?? [];

            foreach ($hookables as $hookableName => $hookable) {
                if (array_key_exists($hookableName, $mergedHookables)) {
                    $hookable = $mergedHookables[$hookableName]->overwriteWith($hookable);
                }

                $mergedHookables[$hookableName] = $hookable;
            }
        }

        return array_values($mergedHookables);
    }
}
