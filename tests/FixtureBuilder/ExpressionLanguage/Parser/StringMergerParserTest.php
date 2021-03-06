<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nelmio\Alice\FixtureBuilder\ExpressionLanguage\Parser;

use Nelmio\Alice\Definition\Value\FakeValue;
use Nelmio\Alice\Definition\Value\ListValue;
use Nelmio\Alice\FixtureBuilder\ExpressionLanguage\ParserInterface;
use Prophecy\Argument;

/**
 * @covers \Nelmio\Alice\FixtureBuilder\ExpressionLanguage\Parser\StringMergerParser
 */
class StringMergerParserTest extends \PHPUnit_Framework_TestCase
{
    public function testIsAParser()
    {
        $this->assertTrue(is_a(StringMergerParser::class, ParserInterface::class, true));
    }

    /**
     * @expectedException \DomainException
     */
    public function testIsNotClonable()
    {
        clone new StringMergerParser(new FakeParser());
    }

    public function testIsInstantiatedWithAParser()
    {
        new StringMergerParser(new FakeParser());
    }

    public function testUsesTheDecoratedParserToParseTheGivenValueAndReturnsItsResultIfResultIsNotAListValue()
    {
        $value = 'foo';
        $expected = new FakeValue();

        $decoratedParserProphecy = $this->prophesize(ParserInterface::class);
        $decoratedParserProphecy->parse($value)->willReturn($expected);
        /** @var ParserInterface $decoratedParser */
        $decoratedParser = $decoratedParserProphecy->reveal();

        $parser = new StringMergerParser($decoratedParser);
        $actual = $parser->parse($value);

        $this->assertEquals($expected, $actual);

        $decoratedParserProphecy->parse(Argument::any())->shouldHaveBeenCalledTimes(1);
    }

    public function testIfTheValueReturnedIsAListValueThenIteratesOverEachValuesToMergeStrings()
    {
        $value = 'foo';

        $decoratedParserProphecy = $this->prophesize(ParserInterface::class);
        $decoratedParserProphecy
            ->parse($value)
            ->willReturn(
                new ListValue([
                    new FakeValue(),
                    'az',
                    'er',
                    'ty',
                    new FakeValue(),
                    'qw',
                    new FakeValue(),
                    'er',
                    'ty',
                ])
            )
        ;
        /** @var ParserInterface $decoratedParser */
        $decoratedParser = $decoratedParserProphecy->reveal();

        $expected = new ListValue([
            new FakeValue(),
            'azerty',
            new FakeValue(),
            'qw',
            new FakeValue(),
            'erty',
        ]);

        $parser = new StringMergerParser($decoratedParser);
        $actual = $parser->parse($value);

        $this->assertEquals($expected, $actual);

        $decoratedParserProphecy->parse(Argument::any())->shouldHaveBeenCalledTimes(1);
    }

    public function testIfNotFunctionFixtureReferenceIsFoundThenTheResultWillRemainUnchanged()
    {
        $value = 'foo';

        $decoratedParserProphecy = $this->prophesize(ParserInterface::class);
        $decoratedParserProphecy
            ->parse($value)
            ->willReturn(
                new ListValue([
                    new FakeValue(),
                    'azerty',
                    new FakeValue(),
                    'qw',
                    new FakeValue(),
                    'erty',
                ])
            )
        ;
        /** @var ParserInterface $decoratedParser */
        $decoratedParser = $decoratedParserProphecy->reveal();

        $expected = new ListValue([
            new FakeValue(),
            'azerty',
            new FakeValue(),
            'qw',
            new FakeValue(),
            'erty',
        ]);

        $parser = new StringMergerParser($decoratedParser);
        $actual = $parser->parse($value);

        $this->assertEquals($expected, $actual);

        $decoratedParserProphecy->parse(Argument::any())->shouldHaveBeenCalledTimes(1);
    }

    /**
     * @dataProvider provideOneElementValues
     */
    public function testIfThereIsOnlyOneElementThenReturnTheElementInsteadOfAValueList($value, $expected)
    {
        $decoratedParserProphecy = $this->prophesize(ParserInterface::class);
        $decoratedParserProphecy->parse(Argument::any())->willReturn($expected);
        /** @var ParserInterface $decoratedParser */
        $decoratedParser = $decoratedParserProphecy->reveal();

        $parser = new StringMergerParser($decoratedParser);
        $actual = $parser->parse('');

        $this->assertEquals($expected, $actual);
    }

    public function provideOneElementValues()
    {
        yield 'one value' => [
            new FakeValue(),
            new FakeValue(),
        ];

        yield 'a list of one value' => [
            new ListValue([new FakeValue()]),
            new FakeValue(),
        ];

        yield 'a function fixture reference' => [
            new ListValue([
                'azer',
                'ty',
            ]),
            'azerty',
        ];
    }
}
