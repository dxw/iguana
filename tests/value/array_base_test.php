<?php

class MyArrayValue extends \Dxw\Iguana\Value\ArrayBase
{
    public static function make(array $value)
    {
        $instance = new \MyArrayValue();
        $instance->value = $value;
        return $instance;
    }

    protected function getDefault()
    {
    }
}

class Value_ArrayBase_Test extends PHPUnit_Framework_TestCase
{
    public function testOffsetExists()
    {
        $superglobal = \MyArrayValue::make([
            'a' => 'b',
        ]);

        $this->assertArrayHasKey('a', $superglobal);
        $this->assertFalse(isset($superglobal['b']));
    }

    public function testOffsetGet()
    {
        $superglobal = \MyArrayValue::make([
            'a' => 'b',
        ]);

        $this->assertEquals('b', $superglobal['a']);
    }

    public function testOffsetSet()
    {
        $superglobal = \MyArrayValue::make([]);

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
        $superglobal = \MyArrayValue::make([]);

        try {
            unset($superglobal['a']);
        } catch (\Exception $e) {
            $this->assertEquals('cannot modify superglobals', $e->getMessage());

            return;
        }

        $this->fail('Expected \\Exception to be thrown.');
    }

    public function testNoStripslashes()
    {
        $superglobal = \MyArrayValue::make([
            'a' => 'a\\\\b',
        ]);

        $this->assertEquals('a\\\\b', $superglobal['a']);
    }

    public function testPassingToConstructor()
    {
        $superglobal = new \MyArrayValue([
            'z' => 'a\\\\b',
        ]);

        $this->assertEquals('a\\\\b', $superglobal['z']);
    }
}
