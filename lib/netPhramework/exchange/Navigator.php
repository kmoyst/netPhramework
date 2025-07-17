<?php

namespace netPhramework\exchange;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Node;
use netPhramework\routing\Route;

class Navigator
{
	private Node $root;
	private Route $route;
	private bool $debugMode = false;

	public function debugOn(): Navigator
	{
		$this->debugMode = true;
		return $this;
	}

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
        if($route === null) return $node;
		$name  = $route->getName();
		if($this->debugMode) {
			echo PHP_EOL."Search for $name...".PHP_EOL;
			print_r($route);
		}
        $child = $node->getChild($name);
		$next  = $route->getNext();
        return $this->traverse($child, $next);
    }
}