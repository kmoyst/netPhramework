<?php

namespace netPhramework\core;

use netPhramework\dispatching\Path;
use netPhramework\exceptions\ComponentNotFound;

class Navigator
{
	private Component $root;
	private Path $guide;

	public function setRoot(Component $root): Navigator
	{
		$this->root = $root;
		return $this;
	}

	public function setGuide(Path $guide): Navigator
	{
		$this->guide = $guide;
		return $this;
	}

	/**
	 * @return Component
	 * @throws ComponentNotFound
	 * @throws Exception
	 */
	public function navigate():Component
	{
        return $this->traverse($this->root, $this->guide);
	}

	/**
	 * @param Component $component
	 * @param Path|null $guide
	 * @return Component
	 * @throws ComponentNotFound
	 * @throws Exception
	 */
    private function traverse(Component $component, ?Path $guide):Component
    {
        if($guide === null) return $component;
        $child = $component->getChild($guide->getName());
        return $this->traverse($child, $guide->getNext());
    }
}