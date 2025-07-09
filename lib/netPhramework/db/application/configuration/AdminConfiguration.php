<?php

namespace netPhramework\db\application\configuration;

use netPhramework\bootstrap\Configuration;
use netPhramework\core\Directory;
use netPhramework\core\Node;
use netPhramework\db\application\mapping\RecordMapper;
use netPhramework\rendering\WrapperConfiguration;

class AdminConfiguration extends Configuration
{
	public function __construct(protected readonly RecordMapper $mapper) {}

	public function createPassiveNode(): Node
	{
		$root = new Directory('')
		;
		new PassiveAssetBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
		return $root;
	}

	public function createActiveNode(): Node
	{
		$root = new Directory('')
		;
		new ActiveAssetBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
		return $root;
	}

	public function configureWrapper(WrapperConfiguration $wrapper): void
	{
		$wrapper->setTitlePrefix('ADMINISTRATION');
	}
}