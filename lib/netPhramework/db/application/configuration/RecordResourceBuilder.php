<?php

namespace netPhramework\db\application\configuration;

use netPhramework\core\BuildableResource;
use netPhramework\core\Resource;
use netPhramework\db\application\mapping\RecordMapper;
use netPhramework\db\core\RecordResource;
use netPhramework\db\core\OneToManyLink;
use netPhramework\db\core\RecordChild;
use netPhramework\db\core\RecordSetChild;

class RecordResourceBuilder
{
	protected ?RecordResource $asset = null;

	public function __construct
	(
		protected RecordMapper $mapper
	)
	{}

	public function newAsset(string $name):self
	{
		$recordSet   = $this->mapper->recordsFor($name);
		$this->asset = new RecordResource($name, $recordSet);
		return $this;
	}

	public function add(Resource $node):self
	{
		if($node instanceof RecordChild)
			$this->asset->recordChildSet->add($node);
		elseif($node instanceof RecordSetChild)
			$this->asset->recordSetChildSet->add($node);
		return $this;
	}

	public function oneToMany(
		RecordResourceStrategy $strategy, string $linkField):self
	{
		$node = new OneToManyLink($strategy->create($this->mapper), $linkField);
		$this->add($node);
		return $this;
	}

	public function strategy(RecordAssetStrategy $strategy):self
	{
		$this->add($strategy->create($this->mapper));
		return $this;
	}

	public function get():RecordResource
	{
		$asset = $this->asset;
		$this->reset();
		return $asset;
	}

	public function commit(BuildableResource $node):self
	{
		$node->add($this->get());
		return $this;
	}

	public function reset():self
	{
		$this->asset = null;
		return $this;
	}
}