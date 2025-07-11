<?php

namespace netPhramework\db\application;

use netPhramework\core\Application;
use netPhramework\db\core\RecordMapper;
use netPhramework\resources\Directory;
use netPhramework\responding\Responder;

class Administration extends Application
{
	public function __construct(protected readonly RecordMapper $mapper) {}

	public function buildPassiveTree(Directory $root): void
	{
		new PassiveTreeBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
	}

	public function buildActiveTree(Directory $root): void
	{
		new ActiveTreeBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
	}

	public function configureResponder(Responder $responder): void
	{
		parent::configureResponder($responder);
		$responder->wrapper->setTitlePrefix('ADMINISTRATION');
	}
}