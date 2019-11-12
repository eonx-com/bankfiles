<?php
declare(strict_types=1);

namespace Tests\EoneoPay\BankFiles\Parsers;

use Mockery\LegacyMockInterface;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren) All Tests extend this class
 */
class TestCase extends PHPUnitTestCase
{
    /**
     * Get mock for given class and set expectations based on given callable.
     *
     * @param string $class
     * @param callable $setExpectations
     *
     * @return \Mockery\LegacyMockInterface
     *
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
     */
    protected function getMockWithExpectations(string $class, callable $setExpectations): LegacyMockInterface
    {
        $mock = \Mockery::mock($class);

        $setExpectations($mock);

        return $mock;
    }

    /**
     * Set the protected/private function to accessible and return reflection method
     *
     * @param string $class
     * @param string $method
     *
     * @return \ReflectionMethod
     *
     * @throws \ReflectionException
     */
    protected function getProtectedMethod(string $class, string $method): ReflectionMethod
    {
        $reflectionClass = new ReflectionClass($class);

        $function = $reflectionClass->getMethod($method);
        $function->setAccessible(true);

        return $function;
    }

    /**
     * Set property to accessible and return reflection property
     *
     * @param string $class
     * @param string $property
     *
     * @return \ReflectionProperty
     *
     * @throws \ReflectionException
     */
    protected function getProtectedProperty(string $class, string $property): ReflectionProperty
    {
        $reflectionClass = new ReflectionClass($class);

        $prop = $reflectionClass->getProperty($property);
        $prop->setAccessible(true);

        return $prop;
    }
}
