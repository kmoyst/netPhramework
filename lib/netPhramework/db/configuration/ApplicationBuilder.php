<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Node;
use netPhramework\db\core\Application;
use netPhramework\db\core\AssetNode;
use netPhramework\db\core\Asset;
use netPhramework\db\core\ChildAsset;
use netPhramework\db\exceptions\ConfigurationException;

class ApplicationBuilder
{
	private Asset $asset;

	public function __construct
	(
	private readonly Application $application
	)
	{}

	public function newAsset(string $name):self
	{
		$this->asset = $this->application->newAsset($name);
		return $this;
	}

	public function add(AssetNode $node):self
	{
		$node->enlist($this->asset);
		return $this;
	}

	public function childAsset(
		AssetStrategy $strategy, string $linkField):self
	{
		$child = $strategy->create($this->mapper);
		$this->add(new ChildAsset($child, $linkField));
		return $this;
	}

	public function strategy(NodeStrategy $strategy):self
	{
		$this->add($strategy->create($this->mapper));
		return $this;
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
		return $this;
	}
}