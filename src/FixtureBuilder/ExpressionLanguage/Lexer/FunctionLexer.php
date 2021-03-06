<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nelmio\Alice\FixtureBuilder\ExpressionLanguage\Lexer;

use Nelmio\Alice\Exception\FixtureBuilder\ExpressionLanguage\MalformedFunctionException;
use Nelmio\Alice\FixtureBuilder\ExpressionLanguage\LexerInterface;
use Nelmio\Alice\NotClonableTrait;

final class FunctionLexer implements LexerInterface
{
    use NotClonableTrait;

    /** @internal */
    const DELIMITER= '___##';

    /**
     * @var LexerInterface
     */
    private $decoratedLexer;

    /**
     * @var FunctionTokenizer
     */
    private $functionTokenizer;

    public function __construct(LexerInterface $decoratedLexer)
    {
        $this->decoratedLexer = $decoratedLexer;
        $this->functionTokenizer = new FunctionTokenizer();
    }

    /**
     * {@inheritdoc}
     *
     * @throws MalformedFunctionException
     */
    public function lex(string $value): array
    {
        if (false === $this->functionTokenizer->isTokenized($value)) {
            $value = $this->functionTokenizer->tokenize($value);
        }

        return $this->decoratedLexer->lex($value);
    }

}
