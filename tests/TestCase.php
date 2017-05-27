<?php

namespace Test;

use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * Create mock class.
     *
     * @param  string  $class
     * @param  array   $args
     * @return \Mockery\MockInterface
     */
    protected function mock($class, array $args = [])
    {
        return Mockery::mock($class, $args)->makePartial();
    }

    /**
     * Create mock spy class.
     *
     * @param  string  $class
     * @return \Mockery\MockInterface
     */
    protected function spy($class)
    {
        return Mockery::spy($class);
    }

    /**
     * Call protected or private method on object instance.
     *
     * @param  object  $object
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     */
    protected function callMethod($object, $method, array $args = [])
    {
        $class = new ReflectionClass($object);
        $method = $class->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }
}
