<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Directory;
use netPhramework\core\Exception;
use netPhramework\db\core\Asset;
use netPhramework\db\core\NodeManager;
use netPhramework\db\core\RecordNodeSet;
use netPhramework\db\core\RecordSetNodeSet;
use netPhramework\db\core\Node;

class AssetAssembler
{
	protected RecordSetNodeSet $recordSetNodeSet;
	protected RecordNodeSet $recordNodeSet;
	protected NodeManager $nodeManager;

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
		$this->nodeManager   	= new NodeManager(
			$this->recordSetNodeSet, $this->recordNodeSet);
	}

	public function strategy(NodeStrategy $strategy):self
	{
		$this->node($strategy->createNode($this->recordMapper));
		return $this;
	}

	public function node(Node $node):self
	{
		$node->enlist($this->nodeManager);
		return $this;
	}

	/**
	 * @param string $assetName
	 * @return $this
	 * @throws Exception
	 */
	public function commit(string $assetName): self
	{
		$this->directory->composite(new Asset(
			$this->recordMapper->recordsFor($assetName),
			$this->recordSetNodeSet,
			$this->recordNodeSet
		));
		$this->reset();
		return $this;
	}
}