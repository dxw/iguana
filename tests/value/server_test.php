<?php

class Value_Server_Test extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \WP_Mock::setUp();

        \WP_Mock::wpFunction('stripslashes_deep', [
            'return' => function ($array) {
                $newArray = [];
                foreach ($array as $k => $v) {
                    $newArray[$k] = stripslashes($v);
                }

                return $newArray;
            },
        ]);
    }

    public function tearDown()
    {
        \WP_Mock::tearDown();
    }

    public function testUsesCorrectGlobalVariable()
    {
        $_SERVER = [
            'a' => 'b',
            'c' => 'd',
        ];

        $__server = new \Dxw\Iguana\Value\Server();

        $this->assertEquals('b', $__server['a']);
        $this->assertEquals('d', $__server['c']);
        $this->assertFalse(isset($__server['z']));
    }

    public function testStripslashes()
    {
        $_SERVER = [
            'a' => 'b\\\\c',
        ];

        $__server = new \Dxw\Iguana\Value\Server();

        $this->assertEquals('b\\c', $__server['a']);
    }
}
