<?php

namespace netPhramework\core;

use Iterator;

class NodeSet implements Iterator
{
	use HasNodes;

	public function add(Node $node):void { $this->storeNode($node); }
}