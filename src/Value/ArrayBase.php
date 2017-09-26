<?php

namespace Dxw\Iguana\Value;

abstract class ArrayBase implements \ArrayAccess
{
    /** @var array|null */
    protected $value;

    abstract protected function getDefault(): array;

    final public function __construct(array $value=null)
    {
        $this->value = $value;
        if ($this->value === null) {
            $this->value = $this->getDefault();
        }
    }

    final public function offsetExists($offset): bool
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
