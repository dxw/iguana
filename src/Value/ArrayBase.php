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

	final public function offsetExists($offset): bool
	{
		return isset($this->value[$offset]);
	}

	// PHP8 requires this method to have a return type of mixed
	// But that is not PHP7-compatible
	// So this attribute suppresses the warning for now
	#[\ReturnTypeWillChange]
	final public function offsetGet($offset)
	{
		return $this->value[$offset];
	}

	final public function offsetSet($offset, $value): void
	{
		throw new \Exception('cannot modify superglobals');
	}

	final public function offsetUnset($offset): void
	{
		throw new \Exception('cannot modify superglobals');
	}
}
