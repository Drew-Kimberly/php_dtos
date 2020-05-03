<?php

namespace php_dtos\tests;

use PHPUnit\Framework\TestCase;

/**
 * Base PHPUnit test case.
 */
class UnitTestCase extends TestCase
{
    /**
     * Asserts if two arrays are equal by sorting them first.
     *
     * @param array $expected
     * @param array $actual
     * @param string $message
     */
    protected function assertArrayEquals(array $expected, array $actual, $message = '')
    {
        ksort($expected);
        ksort($actual);
        $this->assertEquals($expected, $actual, $message);
    }

    /**
     * Utility method to easily invoke protected/private class methods.
     *
     * @param object $obj
     *   Instance of some class.
     * @param string $name
     *   Name of the method to invoke.
     * @param array $args
     *   Array of method parameters.
     *
     * @return mixed
     *   Whatever the invoked method returns.
     */
    protected static function callMethod($obj, $name, array $args = [])
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }

    /**
     * Utility method to examine the value of protected/private class properties.
     *
     * @param object $obj
     *   Instance of some class.
     * @param string $property_name
     *   Name of the protected/private property to get the value of.
     *
     * @return mixed
     *   Whatever the value of the property is.
     */
    protected static function getProperty($obj, $property_name)
    {
        $class = new \ReflectionClass($obj);
        $property = $class->getProperty($property_name);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }
}
