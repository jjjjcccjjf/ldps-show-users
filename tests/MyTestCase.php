<?php

use PHPUnit_Framework_TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;
use jjjjcccjjf\ShowUsers;

class MyTestCase extends PHPUnit_Framework_TestCase {

    // Adds Mockery expectations to the PHPUnit assertions count.
    use MockeryPHPUnitIntegration;

    protected function setUp() {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown() {
        Monkey\tearDown();
        parent::tearDown();
    }

    public function testAddHooksActuallyAddsHooks()
    {
        $class = new jjjjcccjjf\ShowUsers();
        $class->addHooks();

        self::assertTrue( has_action('init', 'Some\Name\Space\MyClass->init()', 20) );
    }
}