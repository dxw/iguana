<?php

class Value_Get_Test extends PHPUnit_Framework_TestCase
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
        $_GET = [
            'a' => 'b',
            'c' => 'd',
        ];

        $__get = new \Dxw\Iguana\Value\Get();

        $this->assertEquals('b', $__get['a']);
        $this->assertEquals('d', $__get['c']);
        $this->assertFalse(isset($__get['z']));
    }

    public function testStripslashes()
    {
        $_GET = [
            'a' => 'b\\\\c',
        ];

        $__get = new \Dxw\Iguana\Value\Get();

        $this->assertEquals('b\\c', $__get['a']);
    }
}
