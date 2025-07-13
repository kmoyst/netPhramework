<?php

namespace netPhramework\db\application;

use netPhramework\db\nodes\Asset;
use netPhramework\db\nodes\Branch;
use netPhramework\db\nodes\RecordChild;
use netPhramework\db\nodes\RecordSetChild;
use netPhramework\db\core\RecordMapper;
use netPhramework\nodes\Directory;
use netPhramework\nodes\Node;

class DynamicNodeBuilder
{
	protected ?Asset $resource;

	public function __construct
	(
	protected readonly RecordMapper $mapper
	)
	{
		$this->reset();
	}

	private function reset():void
	{
		$this->resource = null;
	}

	public function new(string $name):self
	{
		$recordSet = $this->mapper->recordsFor($name);
		$this->resource = new Asset($name, $recordSet);
		return $this;
	}

	public function add(Node $node):self
	{
		if($node instanceof RecordChild)
			$this->resource->recordChildSet->add($node);
		elseif($node instanceof RecordSetChild)
			$this->resource->recordSetChildSet->add($node);
		return $this;
	}

	public function branch(
		RecordResourceStrategy $strategy, string $linkField):self
	{
		$node = new Branch($strategy->create($this->mapper), $linkField);
		$this->add($node);
		return $this;
	}

	public function strategy(RecordAssetStrategy $strategy):self
	{
		$this->add($strategy->create($this->mapper));
		return $this;
	}

	public function get():Asset
	{
		$resource = $this->resource;
		$this->reset();
		return $resource;
	}

	/**
	 * @param Directory $directory
	 * @return $this
	 */
	public function commit(Directory $directory):self
	{
		$directory->add($this->get());
		return $this;
	}
}