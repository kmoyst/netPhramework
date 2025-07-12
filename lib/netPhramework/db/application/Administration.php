<?php

namespace netPhramework\db\application;

use netPhramework\core\Application;
use netPhramework\db\core\RecordMapper;
use netPhramework\resources\Directory;

readonly class Administration implements Application
{
	public function __construct(protected RecordMapper $mapper) {}

	public function buildPassiveNode(Directory $root): void
	{
		new PassiveNodeBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
	}

	public function buildActiveNode(Directory $root): void
	{
		new ActiveNodeBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
	}
}