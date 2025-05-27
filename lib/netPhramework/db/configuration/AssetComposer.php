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
	protected RecordChildSet $recordChildSet;
	protected RecordMapper $mapper;
	protected DirectoryAdapter $directoryAdapter;
	protected AssetCompositeAdapter $activeAdapter;

	public function __construct(RecordMapper $mapper, Directory $directory)
	{
		$this->directoryAdapter = new DirectoryAdapter($directory);
		$this->mapper 			= $mapper;
		$this->newAssembly();
	}

	public function setDirectory(Directory $directory):self
	{
		$this->directoryAdapter = new DirectoryAdapter($directory);
		$this->activeAdapter 	= $this->directoryAdapter;
		return $this;
	}

	protected function newAssembly():void
	{
		$this->activeAdapter 	= $this->directoryAdapter;
		$this->recordSetNodeSet = new RecordSetProcessSet();
		$this->recordChildSet  	= new RecordChildSet();
	}

	public function childAsset(string $linkField):self
	{
		$this->activeAdapter = new RecordChildSetAdapter()
				->setAssetLinkField($linkField)
				->setChildSet($this->recordChildSet)
			;
		return $this;
	}

	public function strategy(NodeStrategy $strategy):self
	{
		$this->node($strategy->createNode($this->mapper));
		return $this;
	}

	public function node(Node $node):self
	{
		if($node instanceof RecordChild)
			$this->recordChildSet->add($node);
		elseif($node instanceof RecordSetProcess)
			$this->recordSetNodeSet->add($node);
		return $this;
	}

	public function commit(string $assetName): self
	{
		$this->activeAdapter->addAsset(new Asset(
			$this->mapper->recordsFor($assetName),
			$this->recordChildSet,
			$this->recordSetNodeSet
		));
		$this->newAssembly();
		return $this;
	}
}