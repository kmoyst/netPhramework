<?php

namespace netPhramework\db\core;

use netPhramework\core\NodeSet;

class RecordSetChildSet extends NodeSet
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