<?php

namespace Dxw\Iguana\Value;

abstract class ArrayBase implements \ArrayAccess
{
    protected $value;

    abstract protected function getDefault() /* : array */;

    final public function __construct(array $value=null)
    {
        if ($value === null) {
            $this->value = $this->getDefault();
        } else {
            $this->value = $value;
        }
    }

    final public function offsetExists($offset)
    {
        return isset($this->value[$offset]);
    }

    final public function offsetGet($offset)
    {
        return $this->value[$offset];
    }

    final public function offsetSet($offset, $value)
    {
        throw new \Exception('cannot modify superglobals');
    }

    final public function offsetUnset($offset)
    {
        throw new \Exception('cannot modify superglobals');
    }
}
