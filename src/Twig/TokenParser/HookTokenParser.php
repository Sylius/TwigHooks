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

namespace Sylius\TwigHooks\Twig\TokenParser;

use Sylius\TwigHooks\Twig\Node\HookNode;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

final class HookTokenParser extends AbstractTokenParser
{
    public const TAG = 'hook';

    public function parse(Token $token): Node
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $hooksNames = $this->parser->getExpressionParser()->parseExpression();

        $hookContext = null;
        if ($stream->nextIf(Token::NAME_TYPE, 'with')) {
            $hookContext = $this->parser->getExpressionParser()->parseMultitargetExpression();
        }

        $only = false;
        if ($stream->nextIf(Token::NAME_TYPE, 'only')) {
            $only = true;
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        return new HookNode($hooksNames, $hookContext, $only, $lineno, $this->getTag());
    }

    public function getTag(): string
    {
        return self::TAG;
    }
}
