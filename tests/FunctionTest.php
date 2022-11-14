<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require "functions.php";

final class FunctionTest extends TestCase
{
    public function testPushAndPop(): void
    {
        $stack = [];
        $this->assertSame(0, count($stack));

        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[0]);
        $this->assertSame(1, count($stack));

        $this->assertSame('foo', array_pop($stack));
        $this->assertSame(0, count($stack));
    }
 
    /**
     * Call the deleteFunction with no parameters
     * Expected result: the guestbook is deleted ... 
     */
    // public function testDelete(): void {
    //   global $config;
    //   $_GET['t'] = 1;
    //   deleteGuestbook(); // make the function call that we are testing

    //   // // now check the results
    //   $this->assertEquals("", file_get_contents($config['test_db']));
    // }
}