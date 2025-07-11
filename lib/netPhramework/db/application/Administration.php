<?php

namespace netPhramework\db\application;

use netPhramework\application\Configurator;
use netPhramework\db\core\RecordMapper;
use netPhramework\resources\Directory;
use netPhramework\responding\Responder;

class Administration extends Configurator
{
	public function __construct(protected readonly RecordMapper $mapper) {}

	public function configurePassiveNode(Directory $root): void
	{
		new PassiveTreeBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
	}

	public function configureActiveNode(Directory $root): void
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