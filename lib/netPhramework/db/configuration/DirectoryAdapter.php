<?php

namespace netPhramework\db\configuration;

use netPhramework\core\Directory;
use netPhramework\db\core\Asset;

class DirectoryAdapter implements AssetCompositeAdapter
{
	private Directory $directory;

	/**
	 * @param Directory $directory
	 */
	public function __construct(Directory $directory)
	{
		$this->directory = $directory;
	}

	public function addAsset(Asset $asset): void
	{
		$this->directory->add($asset);
	}
}