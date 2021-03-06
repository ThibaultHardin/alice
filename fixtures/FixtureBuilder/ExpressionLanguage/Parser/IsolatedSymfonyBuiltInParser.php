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

use Nelmio\Alice\FixtureBuilder\ExpressionLanguage\ParserInterface;
use Nelmio\Alice\Symfony\KernelIsolatedServiceCall;

class IsolatedSymfonyBuiltInParser implements ParserInterface
{
    /**
     * @inheritdoc
     */
    public function parse(string $value)
    {
        return KernelIsolatedServiceCall::call(
            'nelmio_alice.fixture_builder.expression_language.parser',
            function (ParserInterface $parser) use ($value) {
                return $parser->parse($value);
            }
        );
    }
}
