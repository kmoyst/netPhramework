<?php

namespace netPhramework\core;

use netPhramework\locating\Path;
use netPhramework\exceptions\ComponentNotFound;

class Navigator
{
	private Node $root;
	private Path $path;

	public function setRoot(Node $root): Navigator
	{
		$this->root = $root;
		return $this;
	}

	public function setPath(Path $path): Navigator
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * @return Node
	 * @throws ComponentNotFound
	 * @throws Exception
	 */
	public function navigate():Node
	{
        return $this->traverse($this->root, $this->path);
	}

	/**
	 * @param Node $component
	 * @param Path|null $path
	 * @return Node
	 * @throws ComponentNotFound
	 * @throws Exception
	 */
    private function traverse(Node $component, ?Path $path):Node
    {
        if($path === null) return $component;
        $child = $component->getChild($path->getName());
        return $this->traverse($child, $path->getNext());
    }
}