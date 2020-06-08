<?php

// declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use jjjjcccjjf\ShowUsers;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;
use Brain\Monkey\Functions;

class WordpressTest extends TestCase {

    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        Monkey\setUp();
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
    }

    function testInit()
    {
        // $class = new \jjjjcccjjf\ShowUsers\LdpsShowUsers();
        // $class->hookAll();
        // self::assertTrue( has_action('admin_menu', [ $class, 'admin_menu' ]) );
        // self::assertTrue( has_filter('the_title', [ MyClass::class, 'the_title' ] ) );
        // var_dump($class); die();
        // $this->assertTrue(empty([]));
        // self::assertTrue( has_action('admin_menu', 'Some\Name\Space\MyClass->init()', 20) );
        // 
        // self::assertTrue( has_action('admin_menu', 'jjjjcccjjf\ShowUsers\LdpsShowUsers->linkVirtualPageUrl()') );
        // 
        
        // Functions\when( 'add_menu_page' )->justReturn( true );
        // $class->addHooks();
        // 
        // $stub = new \jjjjcccjjf\ShowUsers\LdpsShowUsers();
        // fwrite( STDERR, print_r( \jjjjcccjjf\ShowUsers\LdpsShowUsers::class, true ) );
        // $stub = self::getMockForAbstractClass( \jjjjcccjjf\ShowUsers\LdpsShowUsers::class );
        // $stub_class = get_class( $stub );
        // $base = new \EFormStub\StubAdminBase();
        // We expect admin_menu action to have been added when calling register
        // self::assertTrue( has_action( 'admin_menu', "{$stub_class}->linkVirtualPageUrl()" ) );

        // self::assertTrue( has_action('admin_menu', 'jjjjcccjjf\ShowUsers\LdpsShowUsers->linkVirtualPageUrl()') );


        // Functions\expect( 'get_option' )
        //     ->once() // called once
        //     ->with( 'ldps_show_users', [] ) // with specified arguments, like get_option( 'plugin-settings', [] );
        //     ->andReturn( [
        //       'virtual_slug' => 'show-users',
        //       'use_default_style' => 1,
        // ] ); // what it should return?
    }

}