<?php

namespace netPhramework\db\core;

class RecordSetChildSet
{
	private array $children = [];

	public function add(RecordSetChild $child):self
	{
		$this->children[$child->getName()] = $child;
		return $this;
	}

	public function get(string $name):RecordSetChild
	{
		return $this->children[$name];
	}
}