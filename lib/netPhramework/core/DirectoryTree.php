<?php

namespace netPhramework\core;

class DirectoryTree extends NodeSet
{
	public function add(Node $node):void { $this->storeNode($node); }
}