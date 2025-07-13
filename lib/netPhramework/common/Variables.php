<?php

namespace netPhramework\common;
use Iterator;
use netPhramework\exceptions\VariableMissing;
use netPhramework\rendering\Encodable;

/**
 * @phpstan-type Variable Encodable|string|iterable|null
 */
class Variables implements Iterator
{
	use KeyedIterator;

    public function __clone():void
    {
        foreach ($this->items as $k => $v)
            if(is_object($v)) $this->items[$k] = clone $v;
    }

	public function merge(iterable $iterable, bool $overwrite = true):self
	{
		foreach ($iterable as $k => $v)
			if($this->has($k) && !$overwrite) continue;
			else $this->items[$k] = is_object($v) ? clone $v : $v;
		return $this;
	}

	/**
	 * @return Variable
	 */
	public function current(): Encodable|string|iterable|null
	{
		return current($this->items);
	}

	/**
	 * @param string $key
	 * @return Variable
	 * @throws VariableMissing
	 */
	public function get(string $key): Encodable|string|iterable|null
	{
		if($this->has($key)) return $this->items[$key];
		throw new VariableMissing("No value in Variables with name: $key");
	}

	/**
	 * @param string $key
	 * @return Variable
	 */
	public function getOrNull(string $key): Encodable|string|iterable|null
	{
		return $this->has($key) ? $this->items[$key] : null;
	}

	/**
	 * @param string $key
	 * @param Variable $value
	 * @return $this
	 */
	public function add(string $key, Encodable|string|iterable|null $value):self
	{
		$this->items[$key] = $value;
		return $this;
	}

	public function remove(string $key):self
	{
		if($this->has($key)) unset($this->items[$key]);
		return $this;
	}

	public function clear():self
	{
		$this->items = [];
		return $this;
	}

	public function toArray():array
	{
		return (clone $this)->items;
	}
}