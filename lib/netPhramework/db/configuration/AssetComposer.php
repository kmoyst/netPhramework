<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Directory;
use netPhramework\core\Node;
use netPhramework\db\core\Asset;
use netPhramework\db\core\RecordChild;
use netPhramework\db\core\RecordChildSet;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\core\RecordSetProcessSet;

class AssetComposer
{
	protected RecordSetProcessSet $recordSetNodeSet;
	protected RecordChildSet $recordNodeSet;
	protected RecordMapper $mapper;
	protected Directory $directory;

	public function __construct(RecordMapper $mapper, Directory $directory)
	{
		$this->mapper = $mapper;
		$this->directory = $directory;
		$this->reset();
	}

	protected function reset():void
	{
		$this->recordSetNodeSet = new RecordSetProcessSet();
		$this->recordNodeSet  	= new RecordChildSet();
	}

	public function strategy(NodeStrategy $strategy):self
	{
		$this->node($strategy->createNode($this->mapper));
		return $this;
	}

	public function node(Node $node):self
	{
		if($node instanceof RecordChild)
			$this->recordNodeSet->add($node);
		elseif($node instanceof RecordSetProcess)
			$this->recordSetNodeSet->add($node);
		return $this;
	}

	public function commit(string $assetName): self
	{
		$this->directory->add(new Asset(
			$this->mapper->recordsFor($assetName),
			$this->recordNodeSet,
			$this->recordSetNodeSet
		));
		$this->reset();
		return $this;
	}
}