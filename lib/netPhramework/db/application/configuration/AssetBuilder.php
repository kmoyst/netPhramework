<?php

namespace netPhramework\db\application\configuration;

use netPhramework\core\BuildableNode;
use netPhramework\core\Node;
use netPhramework\db\application\mapping\RecordMapper;
use netPhramework\db\core\Asset;
use netPhramework\db\core\AssetLink;
use netPhramework\db\core\RecordChild;
use netPhramework\db\core\RecordSetChild;

class AssetBuilder
{
	protected ?Asset $asset = null;

	public function __construct
	(
		protected RecordMapper $mapper
	)
	{}

	public function newAsset(string $name):self
	{
		$recordSet   = $this->mapper->recordsFor($name);
		$this->asset = new Asset($name, $recordSet);
		return $this;
	}

	public function add(Node $node):self
	{
		if($node instanceof RecordChild)
			$this->asset->recordChildSet->add($node);
		elseif($node instanceof RecordSetChild)
			$this->asset->recordSetChildSet->add($node);
		return $this;
	}

	public function childAsset(AssetStrategy $strategy, string $linkField):self
	{
		$this->add(new AssetLink($strategy->create($this->mapper), $linkField));
		return $this;
	}

	public function strategy(NodeStrategy $strategy):self
	{
		$this->add($strategy->create($this->mapper));
		return $this;
	}

	public function get():Asset
	{
		return $this->asset;
	}

	public function commit(BuildableNode $node):self
	{
		$node->add($this->get());
		$this->reset();
		return $this;
	}

	public function reset():self
	{
		$this->asset = null;
		return $this;
	}
}