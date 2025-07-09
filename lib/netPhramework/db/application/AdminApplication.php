<?php

namespace netPhramework\db\application;

use netPhramework\bootstrap\Application;
use netPhramework\core\Directory;
use netPhramework\core\Resource;
use netPhramework\db\core\RecordMapper;
use netPhramework\rendering\WrapperConfiguration;

class AdminApplication extends Application
{
	public function __construct(protected readonly RecordMapper $mapper) {}

	public function createPassiveNode(): Resource
	{
		$root = new Directory('')
		;
		new PassiveRecordResourceBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
		return $root;
	}

	public function createActiveNode(): Resource
	{
		$root = new Directory('')
		;
		new ActiveRecordResourceBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
		return $root;
	}

	public function configureWrapper(WrapperConfiguration $wrapper): void
	{
		$wrapper->setTitlePrefix('ADMINISTRATION');
	}
}