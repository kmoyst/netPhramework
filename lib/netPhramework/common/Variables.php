<?php

namespace netPhramework\common;
use Iterator;
use netPhramework\core\Exception;
use netPhramework\rendering\Encodable;

class Variables implements Iterator
{
    private array $values = [];

    public function __clone():void
    {
        foreach ($this->values as $k => $v)
            if(is_object($v)) $this->values[$k] = clone $v;
    }

	public function toArray():array
	{
		return (clone $this)->values;
	}

    public function add(string $key,
						string|Encodable|iterable|null $value):Variables
    {
        $this->values[$key] = $value;
        return $this;
    }

	/**
	 * @param string $key
	 * @return string|Encodable|iterable|null
	 * @throws Exception
	 */
	public function get(string $key): string|Encodable|iterable|null
	{
		if($this->has($key)) return $this->values[$key];
		throw new Exception("No value in Variables with name: $key");
	}

	public function getOrNull(string $key): string|Encodable|iterable|null
	{
		return $this->has($key) ? $this->values[$key] : null;
	}

	public function has(string $key):bool
	{
		return array_key_exists($key, $this->values);
	}

	public function remove(string $key):self
	{
		if($this->has($key)) unset($this->values[$key]);
		return $this;
	}

    public function merge(iterable $iterable, bool $overwrite = true):Variables
    {
        foreach ($iterable as $k => $v)
            if($this->has($k) && !$overwrite) continue;
			else $this->values[$k] = is_object($v) ? clone $v : $v;
        return $this;
    }

	public function clear():Variables
	{
		$this->values = [];
		return $this;
	}

    public function current(): mixed
    {
        return current($this->values);
    }

    public function next(): void
    {
        next($this->values);
    }

    public function key(): string
    {
        return key($this->values);
    }

    public function valid(): bool
    {
        return key($this->values) !== null;
    }

    public function rewind(): void
    {
        reset($this->values);
    }
}