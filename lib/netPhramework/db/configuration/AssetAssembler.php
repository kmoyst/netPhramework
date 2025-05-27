<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Directory;
use netPhramework\db\core\Asset;
use netPhramework\db\core\AssetNode;
use netPhramework\db\core\RecordNodeSet;
use netPhramework\db\core\RecordSetNodeSet;

class AssetAssembler
{
	protected RecordSetNodeSet $recordSetNodeSet;
	protected RecordNodeSet $recordNodeSet;
	protected AssetNodeManager $nodeManager;

	public function __construct(
		protected readonly Directory $directory,
		protected readonly RecordMapper $recordMapper)
	{
		$this->reset();
	}

	protected function reset():void
	{
		$this->recordSetNodeSet = new RecordSetNodeSet();
		$this->recordNodeSet  	= new RecordNodeSet();
		$this->nodeManager   	= new AssetNodeManager(
			$this->recordSetNodeSet, $this->recordNodeSet);
	}

	public function strategy(AssetNodeStrategy $strategy):self
	{
		$this->node($strategy->createNode($this->recordMapper));
		return $this;
	}

	public function node(AssetNode $node):self
	{
		$node->enlist($this->nodeManager);
		return $this;
	}

	public function commit(string $assetName): self
	{
		$this->directory->addChild(new Asset(
			$this->recordMapper->recordsFor($assetName),
			$this->recordSetNodeSet,
			$this->recordNodeSet
		));
		$this->reset();
		return $this;
	}
}