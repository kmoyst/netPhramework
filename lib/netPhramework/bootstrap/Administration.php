<?php

namespace netPhramework\bootstrap;

use netPhramework\core\Directory;
use netPhramework\db\application\ActiveTreeBuilder;
use netPhramework\db\application\PassiveTreeBuilder;
use netPhramework\db\core\RecordMapper;
use netPhramework\db\exceptions\TreeBuilderException;
use netPhramework\rendering\WrapperConfiguration;

class Administration extends Application
{
	public function __construct(protected readonly RecordMapper $mapper) {}

	/**
	 * @param Directory $root
	 * @return void
	 * @throws TreeBuilderException
	 */
	public function buildPassiveTree(Directory $root): void
	{
		new PassiveTreeBuilder($this->mapper)
			->addAllAssetsWithDefaults();
	}

	/**
	 * @param Directory $root
	 * @return void
	 * @throws TreeBuilderException
	 */
	public function buildActiveTree(Directory $root): void
	{
		new ActiveTreeBuilder($this->mapper)
			->addAllAssetsWithDefaults();
	}

	public function configureWrapper(WrapperConfiguration $wrapper): void
	{
		$wrapper->setTitlePrefix('ADMINISTRATION');
	}
}