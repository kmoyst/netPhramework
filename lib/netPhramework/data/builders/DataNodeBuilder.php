<?php

namespace netPhramework\data\builders;

use netPhramework\data\core\RecordMapper;
use netPhramework\data\nodes\RecordBranch;
use netPhramework\data\nodes\RecordResource;
use netPhramework\data\nodes\RecordSetComposite;
use netPhramework\data\strategies\RecordResourceStrategy;
use netPhramework\data\strategies\RecordSetCompositeStrategy;
use netPhramework\nodes\Directory;

class DataNodeBuilder
{
	protected ?RecordSetComposite $asset;

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
		$this->asset = new RecordSetComposite($name, $recordSet);
		return $this;
	}

	public function add(RecordResource $resource):self
	{
		$resource->enlist($this->asset);
		return $this;
	}

	public function branch(
		RecordSetCompositeStrategy $strategy, string $linkField):self
	{
		$node = new RecordBranch($strategy->create($this->mapper), $linkField);
		$this->asset->recordNodeSet->add($node);
		return $this;
	}

	public function strategy(RecordResourceStrategy $strategy):self
	{
		$this->add($strategy->create($this->mapper));
		return $this;
	}

	public function get():RecordSetComposite
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