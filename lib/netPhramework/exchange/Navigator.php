<?php

namespace netPhramework\exchange;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Node;
use netPhramework\routing\Route;

class Navigator
{
	private Node $root;
	private Route $route;

	public function setRoot(Node $root): Navigator
	{
		$this->root = $root;
		return $this;
	}

	public function setRoute(Route $route): self
	{
		$this->route = $route;
		return $this;
	}

	/**
	 * @return Node
	 * @throws NodeNotFound
	 */
	public function navigate():Node
	{
		return $this->traverse($this->root, $this->route);
	}

	/**
	 * @throws NodeNotFound
	 */
    private function traverse(Node $node, ?Route $route):Node
    {
//		var_dump($route);
        if($route === null) return $node;
		$name  = $route->getName();
		if($name === null) return $node; // THIS IS WHAT NEEDED TO BE DONE
        $child = $node->getChild($name);
		$next  = $route->getNext();
        return $this->traverse($child, $next);
    }
}