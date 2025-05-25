<?php

namespace netPhramework\core;

use netPhramework\dispatching\Path;
use netPhramework\exceptions\ComponentNotFound;

class Navigator
{
	private Component $root;
	private Path $path;

	public function setRoot(Component $root): Navigator
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
	 * @return Component
	 * @throws ComponentNotFound
	 * @throws Exception
	 */
	public function navigate():Component
	{
        return $this->traverse($this->root, $this->path);
	}

	/**
	 * @param Component $component
	 * @param Path|null $path
	 * @return Component
	 * @throws ComponentNotFound
	 * @throws Exception
	 */
    private function traverse(Component $component, ?Path $path):Component
    {
        if($path === null) return $component;
        $child = $component->getChild($path->getName());
        return $this->traverse($child, $path->getNext());
    }
}