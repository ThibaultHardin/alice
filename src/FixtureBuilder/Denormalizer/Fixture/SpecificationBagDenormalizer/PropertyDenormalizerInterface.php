<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nelmio\Alice\FixtureBuilder\Denormalizer\Fixture\SpecificationBagDenormalizer;

use Nelmio\Alice\Definition\FlagBag;
use Nelmio\Alice\Definition\Property;
use Nelmio\Alice\FixtureInterface;
use Nelmio\Alice\Throwable\DenormalizationThrowable;

interface PropertyDenormalizerInterface
{
    /**
     * Denormalizes a property.
     *
     * @param FixtureInterface $scope See SpecificationsDenormalizerInterface::denormalize()
     * @param string           $name
     * @param mixed            $value
     * @param FlagBag          $flags
     *
     * @throws DenormalizationThrowable
     *
     * @return Property
     */
    public function denormalize(FixtureInterface $scope, string $name, $value, FlagBag $flags): Property;
}
