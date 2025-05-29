<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Directory;
use netPhramework\core\Node;
use netPhramework\db\core\Asset;
use netPhramework\db\core\ChildAsset;
use netPhramework\db\core\RecordChild;
use netPhramework\db\core\RecordChildSet;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\core\RecordSetProcessSet;
use netPhramework\db\exceptions\ConfigurationException;

class AssetBuilder
{
	protected ?RecordSetProcessSet $recordSetNodeSet;
	protected ?RecordChildSet $recordChildSet;
	protected RecordMapper $mapper;
	protected ?Directory $directory;

	public function __construct(RecordMapper $mapper,
								?Directory $directory = null)
	{
		$this->mapper 	 = $mapper;
		$this->directory = $directory;
		$this->reset();
	}

	public function setDirectory(?Directory $directory): self
	{
		$this->directory = $directory;
		return $this;
	}

	public function childAsset(AssetStrategy $strategy, string $linkField):self
	{
		$child = $strategy->create($this->mapper);
		$this->node(new ChildAsset($child, $linkField));
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

	public function get(string $assetName): Asset
	{
		$asset = new Asset(
			$this->mapper->recordsFor($assetName),
			$this->recordChildSet,
			$this->recordSetNodeSet);
		$this->reset();
		return $asset;
	}

	/**
	 * @param string $assetName
	 * @return $this
	 * @throws ConfigurationException
	 */
	public function commit(string $assetName): self
	{
		if($this->directory === null)
			throw new ConfigurationException("No directory for commit");
		$this->directory->add($this->get($assetName));
		$this->reset();
		return $this;
	}

	protected function reset(): void
	{
		$this->recordSetNodeSet = null;
		$this->recordChildSet = null;
	}
}