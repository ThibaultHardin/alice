<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nelmio\Alice\Generator;

interface ObjectGeneratorAwareInterface
{
    /**
     * @param ObjectGeneratorInterface $generator
     *
     * @return static
     */
    public function withGenerator(ObjectGeneratorInterface $generator);
}
