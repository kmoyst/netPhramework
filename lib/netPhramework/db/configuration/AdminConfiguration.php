<?php

namespace netPhramework\db\configuration;

use netPhramework\bootstrap\Configuration;
use netPhramework\core\Directory;
use netPhramework\rendering\WrapperSetup;

class AdminConfiguration extends Configuration
{
	public function __construct(protected readonly RecordMapper $mapper) {}

	public function configurePassiveNode(Directory $directory): void
	{
		$directory->allowIndex();
		new PassiveAssetAssembler($directory, $this->mapper)
			->addAllAssetsWithDefaults()
		;
	}

	public function configureActiveNode(Directory $directory): void
	{
		new ActiveAssetAssembler($directory, $this->mapper)
			->addAllAssetsWithDefaults()
		;
	}

	public function configureWrapper(WrapperSetup $wrapper): void
	{
		$wrapper->setTitlePrefix('ADMINISTRATION');
	}
}