<?php

namespace netPhramework\core;
use Iterator;
class ComponentSet implements Iterator
{
    private array $components = [];

	public function add(Component $component):void
	{
		$this->components[$component->getName()] = $component;
	}

	public function has(string $name):bool
	{
		return array_key_exists($name, $this->components);
	}

	public function get(string $name):Component
	{
		return $this->components[$name];
	}

	public function getNames():array
	{
		return array_keys($this->components);
	}

    public function current(): Component
    {
        return current($this->components);
    }

    public function next(): void
    {
        next($this->components);
    }

    public function key(): string
    {
        return key($this->components);
    }

    public function valid(): bool
    {
        return key($this->components) !== null;
    }

    public function rewind(): void
    {
        reset($this->components);
    }
}