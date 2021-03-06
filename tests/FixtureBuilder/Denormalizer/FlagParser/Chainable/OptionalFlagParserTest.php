<?php

/*
 * This file is part of the Alice package.
 *  
 * (c) Nelmio <hello@nelm.io>
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nelmio\Alice\FixtureBuilder\Denormalizer\FlagParser\Chainable;

use Nelmio\Alice\Definition\FlagBag;
use Nelmio\Alice\FixtureBuilder\Denormalizer\FlagParser\ChainableFlagParserInterface;
use Nelmio\Alice\FixtureBuilder\Denormalizer\FlagParser\FlagParserTestCase;

/**
 * @covers \Nelmio\Alice\FixtureBuilder\Denormalizer\FlagParser\Chainable\OptionalFlagParser
 */
class OptionalFlagParserTest extends FlagParserTestCase
{
    public function setUp()
    {
        $this->parser = new OptionalFlagParser();
    }

    public function testIsAChainableFlagParser()
    {
        $this->assertTrue(is_a(OptionalFlagParser::class, ChainableFlagParserInterface::class, true));
    }

    /**
     * @dataProvider provideOptionals
     */
    public function testCanParseOptionals(string $element, FlagBag $expected = null)
    {
        $this->assertCanParse($element, $expected);
    }
}
