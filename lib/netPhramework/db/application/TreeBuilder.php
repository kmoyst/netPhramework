<?php

namespace netPhramework\db\application;

use netPhramework\db\core\RecordMapper;
use netPhramework\db\assets\AssetResource;
use netPhramework\db\assets\OneToManyLink;
use netPhramework\db\assets\RecordChild;
use netPhramework\db\assets\RecordSetChild;
use netPhramework\resources\Directory;
use netPhramework\resources\Resource;

class TreeBuilder
{
	protected ?AssetResource $resource;

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
		$this->resource = new AssetResource($name, $recordSet);
		return $this;
	}

	public function add(Resource $node):self
	{
		if($node instanceof RecordChild)
			$this->resource->recordChildSet->add($node);
		elseif($node instanceof RecordSetChild)
			$this->resource->recordSetChildSet->add($node);
		return $this;
	}

	public function oneToMany(
		RecordResourceStrategy $strategy, string $linkField):self
	{
		$node = new OneToManyLink($strategy->create($this->mapper), $linkField);
		$this->add($node);
		return $this;
	}

	public function strategy(RecordAssetStrategy $strategy):self
	{
		$this->add($strategy->create($this->mapper));
		return $this;
	}

	public function get():AssetResource
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