<?php

namespace netPhramework\db\core;

use netPhramework\core\HasNodes;

class RecordChildSet extends HasNodes
{
	public function add(RecordChild $node): void
	{
		$this->storeNode($node);
	}

	public function get(string $id): RecordChild
	{
		$this->confirmNode($id);
		return $this->nodes[$id];
	}
}