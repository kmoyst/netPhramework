<?php

namespace netPhramework\core;

use netPhramework\exceptions\ComponentNotFound;
use netPhramework\exceptions\Exception;

final class Directory extends Composite
{
	private array $children = [];

    public function __construct(private readonly string $name) {}

	/**
	 * @param Composite $composite
	 * @return $this
	 * @throws Exception
	 */
    public function composite(Composite $composite):Directory
	{
		if(($name = $composite->getName()) === '')
			throw new Exception("Composite children must have names");
        $this->children[$name] = $composite;
        return $this;
	}

	public function leaf(Leaf $leaf):Directory
	{
		$this->children[$leaf->getName()] = $leaf;
		return $this;
	}

    public function getChild(string $name): Component
    {
        if(!isset($this->children[$name]))
            throw new ComponentNotFound("Not Found: $name");
        return $this->children[$name];
    }

    public function getName(): string
    {
        return $this->name;
    }
}