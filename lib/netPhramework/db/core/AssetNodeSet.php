<?php

namespace netPhramework\db\core;

use Iterator;
use netPhramework\core\HasNodes;

class AssetNodeSet implements Iterator
{
	use HasNodes;

	public function add(AssetNode $node):self
	{
		$this->storeNode($node);
		return $this;
	}

	public function get(string $id): AssetNode
	{
		$this->confirmNode($id);
		return $this->nodes[$id];
	}
}