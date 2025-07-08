<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Directory;
use netPhramework\db\core\Application;
use netPhramework\db\core\ChildAsset;
use netPhramework\db\exceptions\ConfigurationException;

class AppBuilder
{
	public function __construct
	(
	private readonly Application $application
	)
	{}

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