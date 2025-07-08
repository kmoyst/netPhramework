<?php

namespace netPhramework\core;

use netPhramework\exceptions\NodeNotFound;

final class Directory extends Node
{
	use CompositeBehaviour;

	private DirectoryChildSet $children;
    private string|Index $index;

    public function __construct(private readonly string $name)
	{
		$this->children = new DirectoryChildSet();
	}

	public function add(Node $node):self
	{
		$this->children->add($node);
		return $this;
	}

    public function getChild(string $id): Node
    {
        if($this->children->has($id))
            return $this->children->get($id);
        elseif($id === '' && isset($this->index))
		{
			return (is_string($this->index) ?
				new Index($this->index) : $this->index)
				->setNodeSet($this->children);
		}
        else
            throw new NodeNotFound("Not Found: $id");
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