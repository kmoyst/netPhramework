<?php

namespace netPhramework\db\configuration;

use netPhramework\bootstrap\Configuration;
use netPhramework\core\Directory;
use netPhramework\rendering\WrapperConfiguration;

class AdminConfiguration extends Configuration
{
	public function __construct(protected readonly RecordMapper $mapper) {}

	public function configurePassiveNode(Directory $directory): void
	{
		$directory->allowIndex();
		new PassiveAssetComposer($directory, $this->mapper)
			->addAllAssetsWithDefaults()
		;
	}

	public function configureActiveNode(Directory $directory): void
	{
		new ActiveAssetComposer($directory, $this->mapper)
			->addAllAssetsWithDefaults()
		;
	}

	public function configureWrapper(WrapperConfiguration $wrapper): void
	{
		$wrapper->setTitlePrefix('ADMINISTRATION');
	}
}