<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nelmio\Alice\FixtureBuilder\ExpressionLanguage\Parser\TokenParser\Chainable;

use Nelmio\Alice\FixtureBuilder\ExpressionLanguage\Parser\ChainableTokenParserInterface;
use Nelmio\Alice\FixtureBuilder\ExpressionLanguage\Token;
use Nelmio\Alice\FixtureBuilder\ExpressionLanguage\TokenType;

/**
 * @covers \Nelmio\Alice\FixtureBuilder\ExpressionLanguage\Parser\TokenParser\Chainable\EscapedValueTokenParser
 */
class EscapedValueTokenParserTest extends \PHPUnit_Framework_TestCase
{
    public function testIsAChainableTokenParser()
    {
        $this->assertTrue(is_a(EscapedValueTokenParser::class, ChainableTokenParserInterface::class, true));
    }

    /**
     * @expectedException \DomainException
     */
    public function testIsNotClonable()
    {
        clone new EscapedValueTokenParser();
    }

    /**
     * @dataProvider provideTokens
     */
    public function testCanParseEscapedTokens(Token $token, bool $expected)
    {
        $parser = new EscapedValueTokenParser();
        $actual = $parser->canParse($token);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Nelmio\Alice\Exception\FixtureBuilder\ExpressionLanguage\ParseException
     * @expectedExceptionMessage Could not parse the token "" (type: ESCAPED_VALUE_TYPE).
     */
    public function testThrowsAnExceptionIfAMalformedTokenIsGiven()
    {
        $token = new Token('', new TokenType(TokenType::ESCAPED_VALUE_TYPE));

        $parser = new EscapedValueTokenParser();
        $parser->parse($token);
    }

    public function testReturnsEscapedValue()
    {
        $token = new Token('\<', new TokenType(TokenType::ESCAPED_VALUE_TYPE));
        $expected = '<';

        $parser = new EscapedValueTokenParser();
        $actual = $parser->parse($token);

        $this->assertEquals($expected, $actual);
    }

    public function testTheEscapedValueIsDetokenizedBeforeBeingReturned()
    {
        $token = new Token('\<aliceTokenizedFunction(FUNCTION_START__foo__IDENTITY_OR_FUNCTION_END)>', new TokenType(TokenType::ESCAPED_VALUE_TYPE));
        $expected = '<foo()>';

        $parser = new EscapedValueTokenParser();
        $actual = $parser->parse($token);

        $this->assertEquals($expected, $actual);
    }

    public function provideTokens()
    {
        return [
            [
                new Token('', new TokenType(TokenType::DYNAMIC_ARRAY_TYPE)),
                false,
            ],
            [
                new Token('', new TokenType(TokenType::ESCAPED_VALUE_TYPE)),
                true,
            ],
        ];
    }
}
