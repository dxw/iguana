<?php

class Value_Post_Test extends PHPUnit_Framework_TestCase
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
        $_POST = [
            'e' => 'f',
            'g' => 'h',
        ];

        $__post = new \Dxw\Iguana\Value\Post();

        $this->assertEquals('f', $__post['e']);
        $this->assertEquals('h', $__post['g']);
        $this->assertFalse(isset($__post['z']));
    }

    public function testStripslashes()
    {
        $_POST = [
            'a' => 'b\\\\c',
        ];

        $__post = new \Dxw\Iguana\Value\Post();

        $this->assertEquals('b\\c', $__post['a']);
    }
}
