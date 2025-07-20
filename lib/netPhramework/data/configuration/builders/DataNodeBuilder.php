<?php

namespace netPhramework\data\configuration\builders;

use netPhramework\data\configuration\strategies\AssetResourceStrategy;
use netPhramework\data\configuration\strategies\AssetStrategy;
use netPhramework\data\asset\Asset;
use netPhramework\data\asset\AssetResource;
use netPhramework\data\asset\AssetBranch;
use netPhramework\data\record\RecordMapper;
use netPhramework\nodes\Directory;

class DataNodeBuilder
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
		$node = new AssetBranch($strategy->create($this->mapper), $linkField);
		$this->asset->childNodeSet->add($node);
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