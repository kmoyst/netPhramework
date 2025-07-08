<?php

namespace netPhramework\db\core;

use Iterator;
use netPhramework\core\HasNodes;

class RecordChildSet implements Iterator
{
	use HasNodes;

	public function add(RecordChild $child):self
	{
		$this->storeNode($child);
		return $this;
	}

	public function get(string $id):RecordChild
	{
		$this->confirmNode($id);
		return $this->nodes[$id];
	}

}