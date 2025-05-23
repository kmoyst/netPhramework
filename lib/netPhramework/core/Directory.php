<?php

namespace netPhramework\core;

use netPhramework\exceptions\ComponentNotFound;
use netPhramework\exceptions\Exception;

final class Directory extends Composite
{
	private ComponentSet $children;
    private string|Index $index;

    public function __construct(private readonly string $name)
	{
		$this->children = new ComponentSet();
	}

	/**
	 * @param Composite $composite
	 * @return $this
	 * @throws Exception
	 */
    public function composite(Composite $composite):Directory
	{
		if($composite->getName() === '')
			throw new Exception("Composite children must have names");
        $this->children->add($composite);
        return $this;
	}

	public function leaf(Leaf $leaf):Directory
	{
		$this->children->add($leaf);
		return $this;
	}

    public function getChild(string $name): Component
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
            throw new ComponentNotFound("Not Found: $name");
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