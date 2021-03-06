<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nelmio\Alice\Generator\Instantiator\Chainable;

use Nelmio\Alice\Definition\Fixture\SimpleFixture;
use Nelmio\Alice\Definition\SpecificationBagFactory;
use Nelmio\Alice\Entity\Instantiator\AbstractDummy;
use Nelmio\Alice\Entity\Instantiator\DummyWithDefaultConstructor;
use Nelmio\Alice\Entity\Instantiator\DummyWithExplicitDefaultConstructorThrowingException;
use Nelmio\Alice\Entity\Instantiator\DummyWithPrivateConstructor;
use Nelmio\Alice\Entity\Instantiator\DummyWithProtectedConstructor;
use Nelmio\Alice\Entity\Instantiator\DummyWithRequiredParameterInConstructor;
use Nelmio\Alice\Generator\GenerationContext;
use Nelmio\Alice\Generator\Instantiator\ChainableInstantiatorInterface;
use Nelmio\Alice\Generator\ResolvedFixtureSetFactory;

/**
 * @covers \Nelmio\Alice\Generator\Instantiator\Chainable\NullConstructorInstantiator
 */
class NullConstructorInstantiatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NullConstructorInstantiator
     */
    private $instantiator;

    public function setUp()
    {
        $this->instantiator = new NullConstructorInstantiator();
    }

    public function testIsAChainableInstantiator()
    {
        $this->assertTrue(is_a(NullConstructorInstantiator::class, ChainableInstantiatorInterface::class, true));
    }

    /**
     * @expectedException \DomainException
     */
    public function testIsNotClonable()
    {
        clone $this->instantiator;
    }

    public function testCanInstantiateFixtureUsingADefaultConstructor()
    {
        $fixture = new SimpleFixture('dummy', 'Nelmio\Alice\Entity\User', SpecificationBagFactory::create());

        $this->assertTrue($this->instantiator->canInstantiate($fixture));
    }

    public function testIfCannotGetConstructorReflectionTriesToInstantiateObjectWithoutArguments()
    {
        $fixture = new SimpleFixture('dummy', DummyWithDefaultConstructor::class, SpecificationBagFactory::create());
        $set = $this->instantiator->instantiate($fixture, ResolvedFixtureSetFactory::create(), new GenerationContext());

        $expected = new DummyWithDefaultConstructor();
        $actual = $set->getObjects()->get($fixture)->getInstance();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Nelmio\Alice\Exception\Generator\Instantiator\InstantiationException
     * @expectedExceptionMessage Could not instantiate fixture "dummy".
     */
    public function testThrowsAnExceptionIfInstantiatingObjectWithoutArgumentsFails()
    {
        $fixture = new SimpleFixture('dummy', AbstractDummy::class, SpecificationBagFactory::create());
        $this->instantiator->instantiate($fixture, ResolvedFixtureSetFactory::create(), new GenerationContext());
    }

    /**
     * @expectedException \Nelmio\Alice\Exception\Generator\Instantiator\InstantiationException
     * @expectedExceptionMessage Could not instantiate fixture "dummy".
     */
    public function testThrowsAnExceptionIfReflectionFailsWithAnotherErrorThanMethodNotExisting()
    {
        $fixture = new SimpleFixture('dummy', 'Unknown', SpecificationBagFactory::create());
        $this->instantiator->instantiate($fixture, ResolvedFixtureSetFactory::create(), new GenerationContext());
    }

    /**
     * @expectedException \Nelmio\Alice\Exception\Generator\Instantiator\InstantiationException
     * @expectedExceptionMessage Could not instantiate "dummy", constructor has mandatory parameters but no parameters has been given.
     */
    public function testThrowsAnExceptionIfObjectConstructorHasMandatoryParameters()
    {
        $fixture = new SimpleFixture('dummy', DummyWithRequiredParameterInConstructor::class, SpecificationBagFactory::create());
        $this->instantiator->instantiate($fixture, ResolvedFixtureSetFactory::create(), new GenerationContext());
    }

    /**
     * @expectedException \Nelmio\Alice\Exception\Generator\Instantiator\InstantiationException
     * @expectedExceptionMessage Could not instantiate fixture "dummy".
     */
    public function testThrowsAnExceptionIfObjectInstantiationFailsUnderNominalConditions()
    {
        $fixture = new SimpleFixture('dummy', DummyWithExplicitDefaultConstructorThrowingException::class, SpecificationBagFactory::create());
        $this->instantiator->instantiate($fixture, ResolvedFixtureSetFactory::create(), new GenerationContext());
    }

    /**
     * @expectedException \Nelmio\Alice\Exception\Generator\Instantiator\InstantiationException
     * @expectedExceptionMessage Could not instantiate "dummy", constructor is not public.
     */
    public function testThrowsAnExceptionIfObjectConstructorIsPrivate()
    {
        $fixture = new SimpleFixture('dummy', DummyWithPrivateConstructor::class, SpecificationBagFactory::create());
        $this->instantiator->instantiate($fixture, ResolvedFixtureSetFactory::create(), new GenerationContext());
    }

    /**
     * @expectedException \Nelmio\Alice\Exception\Generator\Instantiator\InstantiationException
     * @expectedExceptionMessage Could not instantiate "dummy", constructor is not public.
     */
    public function testThrowsAnExceptionIfObjectConstructorIsProtected()
    {
        $fixture = new SimpleFixture('dummy', DummyWithProtectedConstructor::class, SpecificationBagFactory::create());
        $this->instantiator->instantiate($fixture, ResolvedFixtureSetFactory::create(), new GenerationContext());
    }
}
