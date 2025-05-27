<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Directory;
use netPhramework\core\Node;
use netPhramework\db\core\Asset;
use netPhramework\db\core\RecordChild;
use netPhramework\db\core\RecordChild;
use netPhramework\db\core\RecordChildSet;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\core\RecordSetProcessSet;

class AssetComposer
{
	protected RecordSetProcessSet $recordSetNodeSet;
	protected RecordChildSet $recordNodeSet;
	protected RecordMapper $mapper;
	protected ?CompositeAdapter $adapter;

	public function __construct(
		RecordMapper $mapper,
		?CompositeAdapter $adapter = null)
	{
		$this->mapper = $mapper;
		$this->adapter = $adapter;
		$this->reset();
	}

	/**
	 * Convenience method to wrap a Directory in an adapter
	 *
	 * @param Directory $directory
	 * @return $this
	 */
	public function adaptToDirectory(Directory $directory): self
	{
		$this->setAdapter(new DirectoryAdapter($directory));
		return $this;
	}

	/**
	 * Convenience method to wrap a RecordChild Node in an adapter
	 *
	 * @param RecordChild $child
	 * @return $this
	 */
	public function adaptToRecordChild(RecordChild $child): self
	{
		$this->setAdapter(new RecordChildAdapter($child));
		return $this;
	}

	/**
	 * Adapter that allows the addition of Assets
	 *
	 * @param CompositeAdapter $adapter
	 * @return $this
	 */
	public function setAdapter(CompositeAdapter $adapter): self
	{
		$this->adapter = $adapter;
		return $this;
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
		$this->adapter->addAsset(new Asset(
			$this->mapper->recordsFor($assetName),
			$this->recordNodeSet,
			$this->recordSetNodeSet
		));
		$this->reset();
		return $this;
	}
}