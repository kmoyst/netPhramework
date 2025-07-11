<?php

namespace netPhramework\site;

use netPhramework\exceptions\ResourceNotFound;
use netPhramework\routing\Path;
use netPhramework\resources\Resource;

class Navigator
{
	private Resource $root;
	private Path $path;

	public function setRoot(Resource $root): Navigator
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
	 * @return Resource
	 * @throws ResourceNotFound
	 */
	public function navigate():Resource
	{
        return $this->traverse($this->root, $this->path);
	}

	/**
	 * @throws ResourceNotFound
	 */
    private function traverse(Resource $node, ?Path $path):Resource
    {
        if($path === null) return $node;
        $child = $node->getChild($path->getName());
        return $this->traverse($child, $path->getNext());
    }
}