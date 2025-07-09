<?php

namespace netPhramework\db\core;

class RecordChildSet
{
	private array $children = [];

	public function add(RecordChild $child):self
	{
		$this->children[$child->getNodeId()] = $child;
		return $this;
	}

	public function get(string $name):RecordChild
	{
		return $this->children[$name];
	}
}