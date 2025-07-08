<?php

namespace netPhramework\core;

use netPhramework\exceptions\NodeNotFound;

final class Directory extends Node
{
	use CompositeTrait;

	private NodeSet $children;
    private string|Index $index;

    public function __construct(private readonly string $name)
	{
		$this->children = new NodeSet();
	}

	public function add(Node $node):self
	{
		$this->children->add($node);
		return $this;
	}

    public function getChild(string $name): Node
    {
        if($this->children->has($name))
            return $this->children->get($name);
        elseif($name === '' && isset($this->index))
		{
			return (is_string($this->index) ?
				new Index($this->index) : $this->index)
				->setComponents($this->children);
		}
        else
            throw new NodeNotFound("Not Found: $name");
    }

	public function getName(): string
    {
        return $this->name;
    }

	/**
	 * Allows Directory to dynamically return an Index with reference to the
	 * child components if no leaf exists with name '' (empty string)
	 *
	 * If $index is null, uses a default Index with default template. If it
	 * is a string, it uses the default Index but with the supplied template.
	 * Otherwise, it will use the custom Index object provided.
	 *
	 * @param string|Index|null $index - template, custom or null for default
	 * @return $this
	 */
    public function allowIndex(string|Index|null $index = null):Directory
    {
        $this->index = $index ?? 'index';
        return $this;
    }
}