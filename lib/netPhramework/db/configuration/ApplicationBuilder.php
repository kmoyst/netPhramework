<?php

namespace netPhramework\db\configuration;

use netPhramework\db\core\Application;
use netPhramework\db\core\Asset;
use netPhramework\db\core\AssetNode;
use netPhramework\db\core\ChildAsset;
use netPhramework\db\core\RecordChild;
use netPhramework\db\core\RecordSetChild;

class ApplicationBuilder
{
	protected Asset $asset;

	public function __construct
	(
	protected readonly Application $application
	)
	{}

	public function newAsset(string $name):self
	{
		$recordSet   = $this->application->recordsFor($name);
		$this->asset = new Asset($name, $recordSet);
		return $this;
	}

	public function add(AssetNode $node):self
	{
		if($node instanceof RecordChild)
			$this->asset->recordChildSet->add($node);
		elseif($node instanceof RecordSetChild)
			$this->asset->recordSetChildSet->add($node);
		return $this;
	}

	public function childAsset(AssetStrategy $strategy, string $linkField):self
	{
		$child = $strategy->create($this->application);
		$this->add(new ChildAsset($child, $linkField));
		return $this;
	}

	public function strategy(AssetNodeStrategy $strategy):self
	{
		$this->add($strategy->create($this->application));
		return $this;
	}

	public function get():Asset
	{
		return $this->asset;
	}

	public function commit():self
	{
		$this->application->add($this->get());
		return $this;
	}
}