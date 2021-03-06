<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nelmio\Alice\Symfony\PropertyAccess;

use Nelmio\Alice\NotCallableTrait;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class FakePropertyAccessor implements PropertyAccessorInterface
{
    use NotCallableTrait;

    /**
     * @inheritdoc
     */
    public function setValue(&$objectOrArray, $propertyPath, $value)
    {
        $this->__call(__METHOD__, func_get_args());
    }

    /**
     * @inheritdoc
     */
    public function getValue($objectOrArray, $propertyPath)
    {
        $this->__call(__METHOD__, func_get_args());
    }

    /**
     * @inheritdoc
     */
    public function isWritable($objectOrArray, $propertyPath)
    {
        $this->__call(__METHOD__, func_get_args());
    }

    /**
     * @inheritdoc
     */
    public function isReadable($objectOrArray, $propertyPath)
    {
        $this->__call(__METHOD__, func_get_args());
    }
}
