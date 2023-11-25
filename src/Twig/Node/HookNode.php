<?php

declare(strict_types=1);

namespace Sylius\TwigHooks\Twig\Node;

use Sylius\TwigHooks\Twig\Runtime\HooksRuntime;
use Twig\Compiler;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Node;

final class HookNode extends Node
{
    public function __construct (
        Node $hookNames,
        ?Node $parameters,
        int $lineno,
        string $tag = null
    ) {
        parent::__construct(
            [
                'hook_names' => $hookNames,
                'parameters' => $parameters ?? new ArrayExpression([], $lineno),
            ],
            [],
            $lineno,
            $tag,
        );
    }

    public function compile(Compiler $compiler): void
    {
        $compiler->addDebugInfo($this);

        $compiler->raw(sprintf(
            '$hooksRuntime = $this->env->getRuntime(\'%s\');',
            HooksRuntime::class,
        ))->raw("\n");

        $compiler->raw('echo $hooksRuntime->renderHook(');
        $compiler->subcompile($this->getNode('hook_names'));
        $compiler->raw(', ');
        $compiler->subcompile($this->getNode('parameters'));
        $compiler->raw(");\n");
    }
}
