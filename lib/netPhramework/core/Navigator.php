<?php

namespace netPhramework\core;

use netPhramework\exceptions\ResourceNotFound;
use netPhramework\resources\Node;
use netPhramework\routing\Path;

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
	 * @throws ResourceNotFound
	 */
	public function navigate():Node
	{
        return $this->traverse($this->root, $this->path);
	}

	/**
	 * @throws ResourceNotFound
	 */
    private function traverse(Node $node, ?Path $path):Node
    {
        if($path === null) return $node;
        $child = $node->getChild($path->getName());
        return $this->traverse($child, $path->getNext());
    }
}