<?php

namespace netPhramework\db\application;

use netPhramework\site\Application;
use netPhramework\db\core\RecordMapper;
use netPhramework\resources\Directory;

readonly class Administration implements Application
{
	public function __construct(protected RecordMapper $mapper) {}

	public function buildPassiveNode(Directory $root): void
	{
		new PassiveTreeBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
	}

	public function buildActiveNode(Directory $root): void
	{
		new ActiveTreeBuilder($this->mapper)
			->addAllAssetsWithDefaults($root);
	}
}