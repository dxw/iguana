<?php

class MyArrayValue extends \Dxw\Iguana\Value\ArrayBase
{
    public function __construct(array $value)
    {
        $this->value = $value;
    }
}

class Value_ArrayBase_Test extends PHPUnit_Framework_TestCase
{
    public function testOffsetExists()
    {
        $superglobal = new MyArrayValue([
            'a' => 'b',
        ]);

        $this->assertArrayHasKey('a', $superglobal);
        $this->assertFalse(isset($superglobal['b']));
    }

    public function testOffsetGet()
    {
        $superglobal = new MyArrayValue([
            'a' => 'b',
        ]);

        $this->assertEquals('b', $superglobal['a']);
    }

    public function testOffsetSet()
    {
        $superglobal = new MyArrayValue([]);

        try {
            $superglobal['a'] = 'b';
        } catch (\Exception $e) {
            $this->assertEquals('cannot modify superglobals', $e->getMessage());

            return;
        }

        $this->fail('Expected \\Exception to be thrown.');
    }

    public function testOffsetUnset()
    {
        $superglobal = new MyArrayValue([]);

        try {
            unset($superglobal['a']);
        } catch (\Exception $e) {
            $this->assertEquals('cannot modify superglobals', $e->getMessage());

            return;
        }

        $this->fail('Expected \\Exception to be thrown.');
    }
}
