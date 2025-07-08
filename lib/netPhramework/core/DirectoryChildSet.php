<?php

namespace netPhramework\core;

class DirectoryChildSet extends NodeSet
{
	public function add(Node $node):void { $this->storeNode($node); }
}