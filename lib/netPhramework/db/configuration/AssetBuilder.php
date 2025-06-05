<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Directory;
use netPhramework\core\Node;
use netPhramework\db\core\Asset;
use netPhramework\db\core\ChildAsset;
use netPhramework\db\core\RecordChild;
use netPhramework\db\core\RecordChildSet;
use netPhramework\db\core\RecordSetChild;
use netPhramework\db\core\RecordSetChildSet;
use netPhramework\db\exceptions\ConfigurationException;

class AssetBuilder
{
	protected ?RecordSetChildSet $recordSetChildSet;
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

	public function setMapper(RecordMapper $mapper): self
	{
		$this->mapper = $mapper;
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
		$this->node($strategy->create($this->mapper));
		return $this;
	}

	public function node(Node $node):self
	{
		if($node instanceof RecordChild)
			$this->recordChildSet->add($node);
		elseif($node instanceof RecordSetChild)
			$this->recordSetChildSet->add($node);
		return $this;
	}

	/**
	 * @param string $mappedName
	 * @param string|null $assetName
	 * @return Asset
	 */
	public function get(
		string $mappedName, ?string $assetName = null): Asset
	{
		$asset = new Asset(
			$assetName ?? $mappedName,
			$this->mapper->recordsFor($mappedName),
			$this->recordChildSet,
			$this->recordSetChildSet,
			new isRecordId());
		$this->reset();
		return $asset;
	}

	/**
	 * @param string $mappedName
	 * @param string|null $assetName
	 * @return $this
	 * @throws ConfigurationException
	 */
	public function commit(
		string $mappedName, ?string $assetName = null): self
	{
		if($this->directory === null)
			throw new ConfigurationException("No directory for commit");
		$this->directory->add($this->get($mappedName, $assetName));
		$this->reset();
		return $this;
	}

	protected function reset(): void
	{
		$this->recordSetChildSet = new RecordSetChildSet();
		$this->recordChildSet = new RecordChildSet();
	}
}