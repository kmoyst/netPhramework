<?php

namespace netPhramework\db\configuration\builders;

use netPhramework\db\configuration\strategies\AssetResourceStrategy;
use netPhramework\db\configuration\strategies\AssetStrategy;
use netPhramework\db\core\RecordMapper;
use netPhramework\db\nodes\Asset;
use netPhramework\db\nodes\AssetResource;
use netPhramework\db\nodes\Branch;
use netPhramework\nodes\Directory;

class DynamicNodeBuilder
{
	protected ?Asset $asset;

	public function __construct
	(
	protected readonly RecordMapper $mapper
	)
	{
		$this->reset();
	}

	private function reset():void
	{
		$this->asset = null;
	}

	public function new(string $name):self
	{
		$recordSet = $this->mapper->recordsFor($name);
		$this->asset = new Asset($name, $recordSet);
		return $this;
	}

	public function add(AssetResource $resource):self
	{
		$resource->enlist($this->asset);
		return $this;
	}

	public function branch(
		AssetStrategy $strategy, string $linkField):self
	{
		$node = new Branch($strategy->create($this->mapper), $linkField);
		$this->asset->recordChildSet->add($node);
		return $this;
	}

	public function strategy(AssetResourceStrategy $strategy):self
	{
		$this->add($strategy->create($this->mapper));
		return $this;
	}

	public function get():Asset
	{
		$resource = $this->asset;
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