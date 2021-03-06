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

use Nelmio\Alice\Definition\RangeName;
use Nelmio\Alice\Definition\Value\ChoiceListValue;
use Nelmio\Alice\Definition\Value\FixtureReferenceValue;
use Nelmio\Alice\Exception\FixtureBuilder\ExpressionLanguage\ParseException;
use Nelmio\Alice\FixtureBuilder\Denormalizer\Fixture\Chainable\RangeNameDenormalizer;
use Nelmio\Alice\FixtureBuilder\ExpressionLanguage\Parser\ChainableTokenParserInterface;
use Nelmio\Alice\FixtureBuilder\ExpressionLanguage\Token;
use Nelmio\Alice\FixtureBuilder\ExpressionLanguage\TokenType;
use Nelmio\Alice\NotClonableTrait;

final class FixtureRangeReferenceTokenParser implements ChainableTokenParserInterface
{
    use NotClonableTrait;

    /** @internal */
    const REGEX = RangeNameDenormalizer::REGEX;

    /**
     * @var string Unique token
     */
    private $token;

    public function __construct()
    {
        $this->token = uniqid(__CLASS__);
    }

    /**
     * @inheritdoc
     */
    public function canParse(Token $token): bool
    {
        return $token->getType() === TokenType::RANGE_REFERENCE_TYPE;
    }

    /**
     * Parses expressions such as '$username'.
     *
     * {@inheritdoc}
     *
     * @throws ParseException
     */
    public function parse(Token $token)
    {
        $range = $this->buildRange($token);
        $references = [];
        for ($currentIndex = $range->getFrom(); $currentIndex <= $range->getTo(); $currentIndex++) {
            $fixtureId = str_replace($this->token, $currentIndex, $range->getName());
            $references[] = new FixtureReferenceValue($fixtureId);
        }

        return new ChoiceListValue($references);
    }

    /**
     * @param Token $token
     *
     * @throws ParseException
     *
     * @return RangeName
     *
     * @example
     *  "@user{1..10}" => new RangeName('user', 1, 10)
     */
    private function buildRange(Token $token): RangeName
    {
        $matches = [];
        $name = substr($token->getValue(), 1);
        if (false === $name) {
            throw ParseException::createForToken($token);
        }

        if (1 !== preg_match(self::REGEX, $name, $matches)) {
            throw ParseException::createForToken($token);
        }
        $reference = str_replace(sprintf('{%s}', $matches['range']), $this->token, $name);

        return new RangeName($reference, $matches['from'], $matches['to']);
    }
}
