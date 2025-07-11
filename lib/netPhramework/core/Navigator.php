<?php

namespace netPhramework\core;

use netPhramework\locating\Path;
use netPhramework\exceptions\NodeNotFound;

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
	 * @throws NodeNotFound
	 */
	public function navigate():Node
	{
        return $this->traverse($this->root, $this->path);
	}

	/**
	 * @param Node $node
	 * @param Path|null $path
	 * @return Node
	 * @throws NodeNotFound
	 */
    private function traverse(Node $node, ?Path $path):Node
    {
        if($path === null) return $node;
        $child = $node->getChild($path->getName());
        return $this->traverse($child, $path->getNext());
    }
}