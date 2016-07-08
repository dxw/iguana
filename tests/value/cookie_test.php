<?php

class Value_Cookie_Test extends PHPUnit_Framework_TestCase
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
        $_COOKIE = [
            'a' => 'b',
            'c' => 'd',
        ];

        $__cookie = new \Dxw\Iguana\Value\Cookie();

        $this->assertEquals('b', $__cookie['a']);
        $this->assertEquals('d', $__cookie['c']);
        $this->assertFalse(isset($__cookie['z']));
    }

    public function testStripslashes()
    {
        $_COOKIE = [
            'a' => 'b\\\\c',
        ];

        $__cookie = new \Dxw\Iguana\Value\Cookie();

        $this->assertEquals('b\\c', $__cookie['a']);
    }
}
