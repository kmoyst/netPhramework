<?php

namespace netPhramework\db\core;

use netPhramework\core\HasNodes;

class RecordSetChildSet extends HasNodes
{
	public function add(RecordSetChild $node): void
	{
		$this->storeNode($node);
	}

	public function get(string $id): RecordSetChild
	{
		$this->confirmNode($id);
		return $this->nodes[$id];
	}
}